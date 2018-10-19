<?php

namespace App\Http\ViewComposers;

use App\Category;
use Illuminate\View\View;

/**
 * @author Afanasyev Pavel <bupyc9@gmail.com>
 */
class CategoryComposer
{
    public function compose(View $view): void
    {
        $categories = Category::query()->orderBy('name', 'asc')->get()->toArray();
        $categories = $this->buildTree($categories);

        $view->with('categories', $categories);
    }

    private function buildTree(array $elements, $parentId = null): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] === $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                $element['children'] = $children;

                $branch[] = $element;
            }
        }

        return $branch;
    }
}