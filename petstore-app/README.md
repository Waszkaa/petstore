<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>README</title>
</head>
<body>
    <h1>Pet Store Management System</h1>
    <p>This is a Laravel-based web application for managing pets in a pet store. It interacts with a REST API to perform CRUD operations (Create, Read, Update, Delete) on pets. Users can also upload images for pets and assign categories and tags to them.</p>

    <h2>Features</h2>
    <ul>
        <li>List all available pets</li>
        <li>Search for pets by name, status, category, or tags</li>
        <li>Create new pets and assign categories and tags</li>
        <li>Edit existing pets</li>
        <li>Delete pets</li>
        <li>Upload images for pets</li>
        <li>Paginate the list of pets</li>
    </ul>

    <h2>Requirements</h2>
    <ul>
        <li>PHP 8.0 or higher</li>
        <li>Composer</li>
        <li>Laravel 8.x or higher</li>
        <li>MySQL</li>
    </ul>

    <h2>Installation</h2>
    <ol>
        <li>
            <p><strong>Clone the repository:</strong></p>
            <pre><code>git clone https://github.com/Waszkaa/petstore.git 
            cd petstore
            cd petstore-app
</code></pre>
        </li>
        <li>
            <p><strong>Install dependencies:</strong></p>
            <pre><code>composer install</code></pre>
        </li>
        <li>
            <p><strong>Copy the <code>.env</code> file and configure your environment:</strong></p>
            <pre><code>cp .env.example .env</code></pre>
            <p>Update your <code>.env</code> file with your database credentials and other necessary configurations.</p>
        </li>
        <li>
            <p><strong>Generate an application key:</strong></p>
            <pre><code>php artisan key:generate</code></pre>
        </li>
        <li>
            <p><strong>Run the database migrations:</strong></p>
            <pre><code>php artisan migrate</code></pre>
        </li>
        <li>
            <p><strong>Serve the application:</strong></p>
            <pre><code>php artisan serve</code></pre>
        </li>
        <li>
            <p><strong>Access the application:</strong></p>
            <p>Open your browser and go to <code>http://localhost:8000</code>.</p>
        </li>
    </ol>

    <h2>API Integration</h2>
    <p>This application interacts with the <a href="https://petstore.swagger.io/">Swagger Petstore API</a> to manage pets. The following endpoints are used:</p>
    <ul>
        <li><code>GET /pet/findByStatus</code>: Retrieve a list of pets by status.</li>
        <li><code>POST /pet</code>: Create a new pet.</li>
        <li><code>PUT /pet</code>: Update an existing pet.</li>
        <li><code>DELETE /pet/{petId}</code>: Delete a pet by ID.</li>
        <li><code>POST /pet/{petId}/uploadImage</code>: Upload an image for a pet.</li>
    </ul>
    <p><strong>Note:</strong> Uploading images to the API does not work because the test endpoint on the API's website also does not add images.</p>

    <h2>Usage</h2>
    <h3>Listing Pets</h3>
    <p>Navigate to the homepage to see a list of available pets. Use the search bar to filter pets by name, status, category, or tags.</p>

    <h3>Creating a Pet</h3>
    <ol>
        <li>Click on "Add Pet".</li>
        <li>Fill in the required details (name, status, category, tags).</li>
        <li>Upload an image if desired.</li>
        <li>Click "Add" to save the pet.</li>
    </ol>

    <h3>Editing a Pet</h3>
    <ol>
        <li>Click on the "Edit" button next to the pet you want to edit.</li>
        <li>Update the details as needed.</li>
        <li>Click "Update" to save the changes.</li>
    </ol>

    <h3>Deleting a Pet</h3>
    <ol>
        <li>Click on the "Delete" button next to the pet you want to delete.</li>
        <li>Confirm the deletion.</li>
    </ol>

    <h3>Uploading an Image</h3>
    <ol>
        <li>Edit a pet to access the image upload form.</li>
        <li>Choose a file to upload.</li>
        <li>Click "Upload" to save the image.</li>
    </ol>

    <h2>Troubleshooting</h2>
    <p>Check the <code>storage/logs/laravel.log</code> file for any errors or issues.</p>

    <h2>License</h2>
    <p>This project is open-source and available under the <a href="LICENSE">MIT License</a>.</p>

    <h2>Contributing</h2>
    <p>Contributions are welcome! Please open an issue or submit a pull request for any bugs or enhancements.</p>
</body>
</html>
