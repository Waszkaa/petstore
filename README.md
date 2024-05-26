# Pet Store Management System

This is a Laravel-based web application for managing pets in a pet store. It interacts with a REST API to perform CRUD operations (Create, Read, Update, Delete) on pets. Users can also upload images for pets and assign categories and tags to them.

## Features

- List all available pets
- Search for pets by name, status, category, or tags
- Create new pets and assign categories and tags
- Edit existing pets
- Delete pets
- Upload images for pets
- Paginate the list of pets

## Requirements

- PHP 8.0 or higher
- Composer
- Laravel 8.x or higher
- MySQL

## Installation

- Clone the repository:
  - `git clone [https://github.com/Waszkaa/petstore.git]
  - `cd petstore-app`

- Install dependencies
- Copy the `.env` file and configure your environment
- Generate an application key
- Run the database migrations
- Serve the application
- Access the application at `http://localhost:8000`

## API Integration

This application interacts with the [Swagger Petstore API](https://petstore.swagger.io/) to manage pets. The following endpoints are used:

- `GET /pet/findByStatus`: Retrieve a list of pets by status.
- `POST /pet`: Create a new pet.
- `PUT /pet`: Update an existing pet.
- `DELETE /pet/{petId}`: Delete a pet by ID.
- `POST /pet/{petId}/uploadImage`: Upload an image for a pet.

**Note:** Uploading images to the API does not work because the test endpoint on the API's website also does not add images.

## Usage

### Listing Pets

- Navigate to the homepage to see a list of available pets.
- Use the search bar to filter pets by name, status, category, or tags.

### Creating a Pet

- Click on "Add Pet".
- Fill in the required details (name, status, category, tags).
- Upload an image if desired.
- Click "Add" to save the pet.

### Editing a Pet

- Click on the "Edit" button next to the pet you want to edit.
- Update the details as needed.
- Click "Update" to save the changes.

### Deleting a Pet

- Click on the "Delete" button next to the pet you want to delete.
- Confirm the deletion.

### Uploading an Image

- Edit a pet to access the image upload form.
- Choose a file to upload.
- Click "Upload" to save the image.

## Troubleshooting

- Check the `storage/logs/laravel.log` file for any errors or issues.

## License

This project is open-source and available under the [MIT License](LICENSE).

## Contributing

- Contributions are welcome! Please open an issue or submit a pull request for any bugs or enhancements.
