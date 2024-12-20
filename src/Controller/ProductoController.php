<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Producto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProductoController extends AbstractController
{
    #[Route('/product', name: 'create_product')]
    public function createProduct(EntityManagerInterface $entityManager): Response
    {
        $product = new Producto();
        $product->setNombre('Caramelo');
        $product->setPrecio(22);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }

    #[Route('/product/{id<\d+>}', name: 'product_show')]
    public function show(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {

        $product = $entityManager->getRepository(Producto::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $updatedPrice = $request->query->get('newPrice');
        $oldPrice = $request->query->get('oldPrice');

        return $this->render('product/index.html.twig', ['producto' => $product, 'newPrice' => $updatedPrice, 'oldPrice' => $oldPrice]);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }

    #[Route('/product/edit/{id}', name: 'product_update')]
    public function update(EntityManagerInterface $entityManager, int $id): Response 
    {
        $product = $entityManager->getRepository(Producto::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $oldPrice = $product->getPrecio();
        $newPrice = 44;

        $product->setPrecio($newPrice);
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute("product_show", ['id' => $product->getId(), 'newPrice' => $newPrice, "oldPrice" => $oldPrice]);
    }

    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        $product = $entityManager->getRepository(Producto::class)->find($id);
        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        $entityManager->remove($product);
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->render('product/delete.html.twig', ['producto' => $product]);
    }

    #[Route('/product/list', name: 'product_list')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $productos = $entityManager->getRepository(Producto::class)->findAll();

        return $this->render('product/list.html.twig', ['productos' => $productos]);
    }

}