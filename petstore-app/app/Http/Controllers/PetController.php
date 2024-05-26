<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Pet;
use App\Models\Category;
use App\Models\Tag;

class PetController extends Controller
{
    // Base URL for the API
    private $apiBaseUrl = 'https://petstore.swagger.io/v2';

    // Method to display the list of pets
    public function index(Request $request)
    {
        // Get pets with status 'available' from the API
        $response = Http::withHeaders([
            'Authorization' => 'apiKey special key'
        ])->get("$this->apiBaseUrl/pet/findByStatus", ['status' => 'available']);

        // If the API call is successful
        if ($response->successful()) {
            $apiPets = $response->json();

            // Loop through each pet retrieved from the API
            foreach ($apiPets as $apiPet) {
                // Skip if required fields are missing
                if (!isset($apiPet['id']) || $apiPet['id'] == 0 || !isset($apiPet['name']) || !isset($apiPet['status'])) {
                    continue;
                }

                // Find or create the category
                $category = Category::firstOrCreate(['name' => $apiPet['category']['name'] ?? 'Unknown']);
                
                // Update or create the pet in the database
                $pet = Pet::updateOrCreate(
                    ['api_id' => $apiPet['id']],
                    [
                        'name' => $apiPet['name'],
                        'status' => $apiPet['status'],
                        'category_id' => $category->id,
                        'photo_urls' => $apiPet['photoUrls'] ?? [],
                    ]
                );

                // Synchronize the tags
                $tagIds = [];
                foreach ($apiPet['tags'] ?? [] as $apiTag) {
                    $tag = Tag::firstOrCreate(['name' => $apiTag['name']]);
                    $tagIds[] = $tag->id;
                }
                $pet->tags()->sync($tagIds);
            }
        }

        // Query the pets
        $query = Pet::query();

        // If there's a search query, filter the results
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhereHas('category', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('tags', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        // Paginate the results
        $pets = $query->orderBy('created_at', 'desc')->paginate(10);

        // Return the view with the pets
        return view('pets.index', compact('pets'));
    }

    // Method to show the form for creating a new pet
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $statuses = ['available', 'pending', 'sold'];
        return view('pets.create', compact('categories', 'tags', 'statuses'));
    }

    // Method to store a new pet
    public function store(Request $request)
    {
        // Find or create the category
        $category = Category::firstOrCreate(['name' => $request->category_name]);
        $tags = $request->tags;

        // Send a request to create the pet in the API
        $response = Http::withHeaders([
            'Authorization' => 'apiKey special key'
        ])->post("$this->apiBaseUrl/pet", [
            'name' => $request->name,
            'status' => $request->status,
            'category' => [
                'name' => $category->name,
            ],
            'tags' => array_map(function ($tag) {
                return ['name' => $tag];
            }, $tags)
        ]);

        // If the API call is successful
        if ($response->successful()) {
            $apiPet = $response->json();

            // Check if API returned a valid ID
            if ($apiPet['id'] == 0) {
                return redirect()->back()->with('error', 'API returned invalid ID.');
            }

            // Synchronize the tags
            $tagIds = [];
            foreach ($tags as $tagName) {
                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            // Create the pet in the database
            $pet = Pet::create([
                'api_id' => $apiPet['id'],
                'name' => $request->name,
                'status' => $request->status,
                'category_id' => $category->id,
                'photo_urls' => []
            ]);

            $pet->tags()->sync($tagIds);

            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Send a request to upload the image to the API
                $imageResponse = Http::withHeaders([
                    'Authorization' => 'apiKey special key'
                ])->attach(
                    'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName()
                )->post("$this->apiBaseUrl/pet/{$pet->api_id}/uploadImage", [
                    'additionalMetadata' => 'image for pet',
                ]);

                // If the image upload is successful
                if ($imageResponse->successful()) {
                    $imageUrl = json_decode($imageResponse->body(), true)['message'] ?? null;
                    if ($imageUrl) {
                        $photoUrls = $pet->photo_urls;
                        $photoUrls[] = $imageUrl;
                        $pet->update(['photo_urls' => $photoUrls]);
                    } else {
                        return redirect()->route('pets.index')->with('error', 'Failed to retrieve image URL.');
                    }
                } else {
                    // Log the error if image upload fails
                    \Log::error('Failed to upload image to API', ['response' => $imageResponse->body(), 'status' => $imageResponse->status()]);
                    return redirect()->route('pets.index')->with('error', 'Pet created but failed to upload image.');
                }
            }

            return redirect()->route('pets.index')->with('success', 'Pet created successfully.');
        } else {
            // Log the error if pet creation fails
            \Log::error('Failed to create pet in API', ['response' => $response->body(), 'status' => $response->status()]);
            return redirect()->back()->with('error', 'Failed to create pet.');
        }
    }

    // Method to show the form for editing a pet
    public function edit($id)
    {
        $pet = Pet::findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        $statuses = ['available', 'pending', 'sold'];
        return view('pets.edit', compact('pet', 'categories', 'tags', 'statuses'));
    }

    // Method to update a pet
    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);
        $category = Category::firstOrCreate(['name' => $request->category_name]);
        $tags = $request->tags;

        // Synchronize the tags
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $tagIds[] = $tag->id;
        }

        // Update the pet in the database
        $pet->update([
            'name' => $request->name,
            'status' => $request->status,
            'category_id' => $category->id,
        ]);

        $pet->tags()->sync($tagIds);

        // Send a request to update the pet in the API
        $response = Http::withHeaders([
            'Authorization' => 'apiKey special key'
        ])->put("$this->apiBaseUrl/pet", [
            'id' => $pet->api_id,
            'name' => $request->name,
            'status' => $request->status,
            'category' => [
                'name' => $category->name,
            ],
            'tags' => array_map(function ($tag) {
                return ['name' => $tag];
            }, $tags)
        ]);

        // If the API call is successful
        if ($response->successful()) {
            return redirect()->route('pets.index')->with('success', 'Pet updated successfully.');
        } else {
            // Log the error if pet update fails
            \Log::error('Failed to update pet in API', ['response' => $response->body(), 'status' => $response->status()]);
            return redirect()->back()->with('error', 'Failed to update pet.');
        }
    }

    // Method to delete a pet
    public function destroy($id)
    {
        $pet = Pet::findOrFail($id);

        // Send a request to delete the pet in the API
        $response = Http::withHeaders([
            'Authorization' => 'apiKey special key'
        ])->delete("$this->apiBaseUrl/pet/{$pet->api_id}");

        // If the API call is successful or the pet does not exist in the API
        if ($response->successful() || $response->status() == 404) {
            $pet->delete();
            return redirect()->route('pets.index')->with('success', 'Pet deleted successfully.');
        } else {
            // Log the error if pet deletion fails
            \Log::error('Failed to delete pet from API', ['response' => $response->body(), 'status' => $response->status(), 'headers' => $response->headers()]);
            return redirect()->back()->with('error', 'Failed to delete pet.');
        }
    }

    // Method to upload an image for a pet
    public function uploadImage(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        // Send a request to upload the image to the API
        $response = Http::withHeaders([
            'Authorization' => 'apiKey special key'
        ])->attach(
            'file', file_get_contents($request->file('file')->getRealPath()), $request->file('file')->getClientOriginalName()
        )->post("$this->apiBaseUrl/pet/{$pet->api_id}/uploadImage", [
            'additionalMetadata' => 'image for pet',
        ]);

        // If the image upload is successful
        if ($response->successful()) {
            $imageUrl = json_decode($response->body(), true)['message'] ?? null;
            if ($imageUrl) {
                $photoUrls = $pet->photo_urls;
                $photoUrls[] = $imageUrl;
                $pet->update(['photo_urls' => $photoUrls]);
                return redirect()->route('pets.edit', $id)->with('success', 'Image uploaded successfully.');
            } else {
                // Log the error if image URL retrieval fails
                \Log::error('Failed to retrieve image URL from API', ['response' => $response->body()]);
                return redirect()->back()->with('error', 'Failed to retrieve image URL.');
            }
        }

        // Log the error if image upload fails
        \Log::error('Failed to upload image to API', ['response' => $response->body(), 'status' => $response->status()]);
        return redirect()->back()->with('error', 'Failed to upload image.');
    }
}