# Pobo SDK for PHP

Pobo SDK is a PHP library designed to integrate with the Pobo system. It provides functionality for bulk uploading products, retrieving all products, and fetching categories.

## Installation

```bash
composer require pobo/sdk
```

## Authentication

```php
    try {
        $user = new \Pobo\UserClient(
            'email',
            'password'
        );
    } catch (\Pobo\Exceptions\AuthenticationException $e) {
        echo 'Authentication failed: ' . $e->getMessage();
    }
    
    $client = new \Pobo\PoboClient($user);
```

## Fetch Categories

Retrieve a list of all categories from the system:

```php
    /**
     * @var \Pobo\Libs\Models\CategoryModel[] $categories
     * @throws \Pobo\Exceptions\ApiClientException
    */
    $categories = $client->categories()->list();
    
    foreach ($categories as $category) {
        echo $category->getName();
    }
```

## Fetch Products

Retrieve a list of all products from the system:

```php
    /**
     * @var \Pobo\Libs\Models\ProductModel[] $products
     * @throws \Pobo\Exceptions\ApiClientException
    */
    $products = $client->products()->list();
    
    foreach ($products as $product) {
        echo $product->getName();
    }
```

## Bulk Upload Products

Bulk upload multiple products to the system:

```php
    $bulkImport = $client->products()->bulkImport(
        [
            [
                'guid' => '302b8ad6-07d5-11ec-b98c-0cc47a6c9370',
                'name' => 'Test Product from API',
                'short_description' => 'This is a test product created via API.',
                'is_visible' => true,
                'categories' => [1, 2, 3],
                'images' => [
                    [
                        'src' => 'https://picsum.photos/200/300',
                    ],
                    [
                        'src' => 'https://picsum.photos/200/300',
                        'main_image' => true,
                    ],
                ]
            ]
        ]
    );
    
    print_r($bulkImport);
```

## Example Response

```php
    Array
    (
        [result] => Array
        (
            [success] => 1
            [skipped] => 0
            [errors] => Array()
        )
    )
```
