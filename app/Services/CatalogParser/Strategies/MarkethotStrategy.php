<?php

namespace App\Services\CatalogParser\Strategies;

use App\Category;
use App\Offer;
use App\Product;
use App\Services\CatalogParser\CatalogParserException;
use App\Services\CatalogParser\ParserStrategyInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */
class MarkethotStrategy implements ParserStrategyInterface
{
    /**
     * @param string $url
     * @throws CatalogParserException
     */
    public function process(string $url): void
    {
        try {
            $client = new Client();
            $response = $client->request('GET', $url);

            if (Response::HTTP_OK !== $response->getStatusCode()) {
                throw new CatalogParserException('Not status ok');
            }

            $data = \json_decode($response->getBody()->getContents(), true);
            if (null === $data) {
                throw new CatalogParserException('Invalid json');
            }

            foreach ($data['products'] as $product) {
                $this->processProduct($product);
            }

            $this->deleteOld($data);
        } catch (\InvalidArgumentException | GuzzleException $e) {
            throw new CatalogParserException($e->getMessage(), 0, $e);
        }
    }

    private function processProduct(array $productData): void
    {
        $externalId = $this->generateExternalId((string)$productData['id']);
        $product = Product::withTrashed()->where('external_id', '=', $externalId)->first();

        if (null === $product) {
            $product = new Product();
        }

        $product->fill(
            [
                'name' => (string)$productData['title'],
                'picture' => (string)$productData['image'],
                'description' => (string)$productData['description'],
                'amount' => (int)$productData['amount'],
                'price' => (float)$productData['price'],
                'external_id' => $externalId,
            ]
        )->save();

        if ($product->trashed()) {
            $product->restore();
        }

        foreach ($productData['offers'] as $offer) {
            $this->processOffer($product, $offer);
        }

        $this->processCategories($productData, $product);
    }

    private function processOffer(Product $product, array $offerData): void
    {
        $externalId = $this->generateExternalId((string)$offerData['id']);
        $offer = Offer::withTrashed()->where('external_id', '=', $externalId)->first();

        if (null === $offer) {
            $offer = new Offer();
        }

        $offer->fill(
            [
                'price' => (float)$offerData['price'],
                'amount' => (int)$offerData['amount'],
                'article' => (string)$offerData['article'],
                'external_id' => $externalId,
            ]
        );

        $product->offers()->save($offer);

        if ($offer->trashed()) {
            $offer->restore();
        }
    }

    private function processCategories(array $productData, Product $product): void
    {
        $ids = [];
        foreach ($productData['categories'] as $categoryData) {
            $externalId = $this->generateExternalId((string)$categoryData['id']);
            $category = Category::withTrashed()->where('external_id', '=', $externalId)->first();

            if (null === $category) {
                $category = new Category();
            }

            $category->fill(
                [
                    'name' => (string)$categoryData['title'],
                    'code' => (string)$categoryData['alias'],
                    'external_id' => $externalId,
                ]
            )->save();

            if ($category->trashed()) {
                $category->restore();
            }

            $ids[] = $category->id;

            if (!empty($categoryData['parent'])) {
                $parentExternalId = $this->generateExternalId((string)$categoryData['parent']);
                $parentCategory = Category::whereExternalId($parentExternalId)->first();
                $parentCategory->parent()->save($category);
            }
        }

        !empty($ids) && $product->categories()->sync($ids);
    }

    private function generateExternalId(string $id): string
    {
        return 'markethot_' . $id;
    }

    private function deleteOld(array $data): void
    {
        $products = [];
        $offers = [];
        $categories = [];

        foreach ($data['products'] as $product) {
            $products[$product['id']] = $this->generateExternalId((string)$product['id']);

            foreach ($product['offers'] as $offer) {
                $offers[$offer['id']] = $this->generateExternalId((string)$offer['id']);
            }

            foreach ($product['categories'] as $category) {
                $categories[$category['id']] = $this->generateExternalId((string)$category['id']);
            }
        }

        !empty($products) && Product::query()->whereNotIn('external_id', $products)->delete();
        !empty($categories) && Category::query()->whereNotIn('external_id', $categories)->delete();
        !empty($offers) && Offer::query()->whereNotIn('external_id', $offers)->delete();
    }
}