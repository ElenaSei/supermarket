<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productRepository;

    /**
     * Supermarket constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/add", name="add_product")
     * @param Request $request
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addProduct(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!$this->productRepository->findOneBy(['name' => $product->getName()])) {
                $this->productRepository->save($product);

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('product/add.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/edit/{id}", name="edit_product")
     * @param Request $request
     * @param int $id
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editProduct(int $id, Request $request)
    {
        $product = $this->productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->productRepository->save($product);
            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/edit.html.twig',
                ['form' => $form->createView(),
                'product' => $product]);
    }

    /**
     * @Route("/delete/{id}", name="delete_product")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteProduct(int $id){
        $product = $this->productRepository->find($id);
        $this->productRepository->delete($product);

        return $this->redirectToRoute('homepage');
    }
}
