<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class ProductController extends AbstractController
{
    public function list(ManagerRegistry $mr, RouterInterface $router): Response
    {
        $product = $mr->getManager()->getRepository(Product::class)->findAll();

        if (!$product) {
            return $this->json(['success' => false], status: 404);
        }

        $dataArray = [
            'data' => $product,
            'links' => $router->generate('listProducts')
        ];

        return $this->json($dataArray);
    }

    /** create product
     *
     */
    public function create(Request $request, ManagerRegistry $mr): Response
    {
        $categoryId = (int)$request->request->get('category');
        $product = null;
        if ($categoryId) {
            $category = $mr->getRepository(Category::class)->find($categoryId);
            if ($category) {

                $product = new Product();
                $product->setCategory($category);
                $request->request->remove('category');
                $this->setDataToProduct($request->request->all(), $product);

                $category->addProduct($product);

                $this->saveProduct($product, $mr);
            }
        }

        return $this->json(['success' => true, 'product' => $product], status: 201);

    }

    /** setDataToProduct
     *
     */
    public function setDataToProduct(array $requestData, Product $product)
    {
        foreach ($requestData as $key => $data) {
            $methodName = 'set' . ucfirst($key);
            if (!empty($data) && method_exists($product, $methodName)) {
                $product->{$methodName}($data);
            }
        }
    }

    private function saveProduct(Product $product, ManagerRegistry $mr): void
    {
        $em = $mr->getManager();
        $em->persist($product);
        $em->flush();

    }

    /** show product
     *
     */
    public function read(ManagerRegistry $mr, int $id): Response
    {
        $product = $mr->getRepository(Product::class)->findOneByIdJoinedToCategory($id);

        $category = $product->getCategory();

        //dump($category->getName());

        return $this->json(['success' => true,
            'product' => $product,
            'categoryId' => $category->getId(),
            'category' => $category->getName()], status: 200);
    }
}