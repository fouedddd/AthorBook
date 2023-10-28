<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RoomController extends AbstractController
{
    #[Route('/room', name: 'app_room')]
    public function index(): Response
    {
        return $this->render('room/index.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }
    #[Route('/addroom', name: 'addroom')]
    public function addroom(ManagerRegistry $managerRegistry,Request $req ): Response
    {
        $em=$managerRegistry->getManager();
        $room=new Room();
        $form=$this->createForm(RoomType::class, $room);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($room);
            $em->flush();
            return $this->redirect('showroom');
        }
        return $this->renderForm('room/addromm.html.twig', [
            'f' => $form,
        ]);
    }
    #[Route('/showroom', name: 'showroom')]
    public function showroom(RoomRepository $roomRepository): Response
    {
        $room = $roomRepository->findAll();
       
        return $this->render('room/showroom.html.twig', [
            'room' => $room
        ]);
    }
    #[Route('/modifroom{id}', name: 'modifroom')]
    public function modifroom($id,ManagerRegistry $managerRegistry,Request $req,RoomRepository $roomRepository ): Response
    {
        $em=$managerRegistry->getManager();
        $room=$roomRepository->find($id);
        $form=$this->createForm(RoomType::class, $room);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){
            $em->persist($room);
            $em->flush();
            return $this->redirect('showroom');
        }
        return $this->renderForm('room/addromm.html.twig', [
            'f' => $form,
        ]);
    } 
    #[Route('/Deleteroom{id}', name: 'Deleteroom')]
    public function Deleteroom($id,ManagerRegistry $managerRegistry,RoomRepository $roomRepository): Response
    {
        $em=$managerRegistry->getManager();
        $dataid=$roomRepository->find($id);
        $em->remove($dataid);
        $em->flush();
        return $this->redirect('showroom');

        
       
    } 

}
