<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController
{
    private $promotionRepository;

    /**
     * Supermarket constructor.
     * @param PromotionRepository $promotionRepository
     */
    public function __construct(PromotionRepository $promotionRepository)
    {
        $this->promotionRepository = $promotionRepository;
    }

    /**
     * @Route("/promotion/add", name="add_promotion")
     * @param Request $request
     * @Method({"POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addPromotion(Request $request)
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if (!empty($promotion->getQuantity()) && !empty($promotion->getPrice())) {
                $activePromotion = $promotion->getProduct()->getPromotion();

                if($activePromotion){
                    $activePromotion->setPrice($promotion->getPrice());
                    $activePromotion->setQuantity($promotion->getQuantity());
                    $this->promotionRepository->save($activePromotion);
                } else {
                    $this->promotionRepository->save($promotion);
                }
            }
            return $this->redirectToRoute('homepage');
        }

        return $this->render('promotion/add.html.twig',
            ['form' => $form->createView()]);
    }
}