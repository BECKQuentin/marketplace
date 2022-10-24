<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\City;
use App\Form\CityCreateFormType;
use Symfony\Component\HttpFoundation\Request;

class CityController extends AbstractController
{
    #[Route('admin/cities', name: 'app_cities')]
    // #[Security("is_granted('ROLE_ADMIN')", statusCode: 403, message: "Resource not found.")]
    public function allCities(EntityManagerInterface $entityManager): Response
    {
        $cities = $entityManager->getRepository(City::class)->findAll();

        return $this->render('city/index.html.twig', [
            'cities' => $cities,
        ]);
    }

    #[Route('/admin/city/{id}', name: 'app_city')]
    public function showcity(EntityManagerInterface $entityManager, int $id): Response
    {
        $city = $entityManager->getRepository(City::class)->find($id);

        if (!$city) {
            return $this->redirectToRoute('app_cities');
        } else {
            return $this->render('city/index.html.twig', [
                'cities' => [$city],
            ]);
        }
    }

    #[Route('/admin/city_create', name: 'app_add_city')]
    public function addCity(Request $request, EntityManagerInterface $entityManager): Response
    {
        $city = new City();

        $form = $this->createForm(CityCreateFormType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('app_city', ['id' => $city->getId()]);
        }

        return $this->renderForm('city/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/city_edit/{id}', name: 'app_edit_city')]
    public function editCity(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $city = $entityManager->getRepository(City::class)->find($id);

        // If no city, redirect to list of cities
        if (!$city) {
            return $this->redirectToRoute('app_cities');
        }

        $form = $this->createForm(CityCreateFormType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form->getData();

            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('app_cities');
        }

        return $this->renderForm('city/create.html.twig', [
            'form' => $form,
        ]);
    }
}
