<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $productRepository;
    private $promotionRepository;

    /**
     * Supermarket constructor.
     * @param ProductRepository $productRepository
     * @param PromotionRepository $promotionRepository
     */
    public function __construct(ProductRepository $productRepository, PromotionRepository $promotionRepository)
    {
        $this->productRepository = $productRepository;
        $this->promotionRepository = $promotionRepository;
    }

    /**
     * @Route("/product", name="add_product")
     * @param Request $request
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        if ($request->isMethod('post')){
            $parameters = $request->request->all();
            $product = new Product($parameters['product']['name'], $parameters['product']['price']);

            if (!empty($parameters['promotion']['quantity']) && !empty($parameters['promotion']['price'])){
                $promotion = new Promotion($parameters['promotion']['quantity'], $parameters['promotion']['price']);
                $product->addPromotion($promotion);
            }
            $this->productRepository->save($product);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/add.html.twig');
    }

    /**
     * @Route("/product/{id}", name="edit_product")
     * @param Request $request
     * @param int $id
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editProduct(int $id, Request $request)
    {
        $product = $this->productRepository->find($id);

        if ($request->isMethod('post')){

            $parameters = $request->request->all();
            $product->setName($parameters['product']['name']);
            $product->setPrice($parameters['product']['price']);

            if (!empty($parameters['promotion']['quantity']) && !empty($parameters['promotion']['price'])){

                $promotion = $product->getActivePromotion();
                $promotion->setQuantity($parameters['promotion']['quantity']);
                $promotion->setPrice($parameters['promotion']['price']);
                $this->promotionRepository->update($promotion);
            }
            $this->productRepository->update($product);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/edit.html.twig', ['product' => $product]);
    }
}
