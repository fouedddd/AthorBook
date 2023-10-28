<?php

namespace App\Controller;

use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    #[Route('/car', name: 'app_car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }
    #[Route('/addcar', name: 'addcar')]
    public function addcar(ManagerRegistry $managerRegistry,Request $req ): Response
    {
        $em=$managerRegistry->getManager();
        $car=new Car();
        $form=$this->createForm(CarType::class, $car);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($car);
            $em->flush();
            return $this->redirect('showw');
        }
        return $this->renderForm('car/addcar.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/showw', name: 'showw')]
    public function showw(CarRepository $carRepository): Response
    {
        $car = $carRepository->findAll();
       
        return $this->render('car/showw.html.twig', [
            'car' => $car
        ]);
    }
    #[Route('/modifcar{id}', name: 'modifcar')]
    public function modifcar($id,ManagerRegistry $managerRegistry,Request $req,CarRepository $carRepository ): Response
    {
        $em=$managerRegistry->getManager();
        $car=$carRepository->find($id);
        $form=$this->createForm(CarType::class, $car);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($car);
            $em->flush();
            return $this->redirect('showw');
        }
        return $this->renderForm('car/addcar.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/Deletecar{id}', name: 'Deletecar')]
    public function Deletecar($id,ManagerRegistry $managerRegistry,CarRepository $carRepository): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$carRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirect('showw');

        
       
    }
    #[Route('/show{id}', name: 'show')]
    public function show($id,CarRepository $carRepository): Response
    {
        $car = $carRepository->find($id);
       
        return $this->render('car/show.html.twig', [
            'car' => $car
        ]);
    }
    
    
}
