<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 8.03.20
 * Time: 14:52
 */

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\ProductRepository;
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
                $activePromotion = $promotion->getProduct()->getActivePromotion();

                if($activePromotion){
                    $this->promotionRepository->delete($activePromotion);
                }

                $this->promotionRepository->save($promotion);
            }
            return $this->redirectToRoute('homepage');
        }

        return $this->render('promotion/add.html.twig',
            ['form' => $form->createView()]);
    }
}