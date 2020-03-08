<?php
namespace App\Controller;

use App\Entity\Bill;
use App\Entity\BillItem;
use App\Repository\BillItemRepository;
use App\Repository\BillRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SupermarketController extends AbstractController
{
    private $productRepository;
    private $billRepository;
    private $billItemRepository;

    /**
     * Supermarket constructor.
     * @param ProductRepository $productRepository
     * @param BillRepository $billRepository
     * @param BillItemRepository $billItemRepository
     */
    public function __construct(ProductRepository $productRepository, BillRepository $billRepository,
                                BillItemRepository $billItemRepository)
    {
        $this->productRepository = $productRepository;
        $this->billRepository = $billRepository;
        $this->billItemRepository = $billItemRepository;
    }

    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(): Response
    {
        $products = $this->productRepository->findAll();

        return $this->render('home/index.html.twig',
            ['products' => $products]);
    }

    /**
     * @Route("/make_order", name="make_order")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method({"POST"})
     */
    public function orderAction(Request $request): Response
    {
        if ($request->isXMLHttpRequest()) {
            $params = json_decode($request->request->get('data'));

            $bill = new Bill();
            $date = new \DateTime();
            $bill->setDate($date);

            $this->billRepository->save($bill);

            $items = [];
            foreach ($params->myarray as $p) {
                $product = $this->productRepository->find($p->id);

                if (!isset($items[$product->getName()])) {
                    $item = new BillItem();
                    $item->setProduct($product);
                    $item->setBill($bill);
                    $items[$product->getName()] = $item;
                    $this->billItemRepository->save($item);

                    $bill->addItem($items[$product->getName()]);
                }

                $items[$product->getName()]->addQuantity(1);
                $this->billItemRepository->update($items[$product->getName()]);

            }

            $bill->setTotalPrice();
            $this->billRepository->save($bill);

            $encoders = [ new JsonEncode() ];
            $normalizers = [ new ObjectNormalizer() ];

            $serializer = new Serializer($normalizers, $encoders);

            $jsonContent = $serializer->serialize($bill, 'json', [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }
            ]);
            return new JsonResponse(array('data' => $jsonContent));
        }
        return new Response('This is not ajax!', 200);
    }
}

