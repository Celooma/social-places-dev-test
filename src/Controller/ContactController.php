<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Store;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\ViewModels\UserViewModel;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    #[Route('api/contacts/create', name: 'api_contact_create', methods: 'POST')]
    public function createContact(Request $request, ValidatorInterface $validator,  MailerInterface $mailer, EntityManagerInterface $entityManager): JsonResponse
    {
        $contact = new Contact();
        $request = json_decode($request->getContent(), true);
    
        foreach (['name', 'surname', 'email', 'cellphone', 'gender'] as $field) {
            if(empty($request[$field])){
                return $this->json(['message' => "$field is required"], Response::HTTP_PRECONDITION_FAILED);
            }
            $contact->{sp_setter($field)}($request[$field]);
        }

        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            return $this->json(compact('errors'), Response::HTTP_PRECONDITION_FAILED);
        }


        return $this->json(['message' => 'Contact created' ]);
    }
}
