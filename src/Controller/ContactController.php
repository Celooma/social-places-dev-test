<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Store;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;


class ContactController extends AbstractController
{
    #[Route('api/contacts/create', name: 'api_contact_create', methods: 'POST')]
    public function createContact(Request $request, ValidatorInterface $validator,  MailerInterface $mailer, EntityManagerInterface $entityManager): JsonResponse
    {
        $contact = new Contact();
        $request = json_decode($request->getContent(), true);
    
        foreach (['name', 'surname', 'email', 'cellphone'] as $field) {
            if(empty($request[$field]) ){
                return $this->json(['message' => "$field is required"], Response::HTTP_PRECONDITION_FAILED);
            }
            $contact->{sp_setter($field)}($request[$field]);
        }

        if(!empty($request['gender'])){
            $contact->setGenderFromName($request['gender']);
        }

        $errors = sp_extract_errors($validator->validate($contact));
        if (!empty($errors)) {
            return $this->json(compact('errors'), Response::HTTP_PRECONDITION_FAILED);
        }
        
        $store = $entityManager->getRepository(Store::class)->findOneBy(['id' => $request['store']]);

        $contact->setStore($store);
        $entityManager->persist($contact);
        $entityManager->flush();

        $admins = $entityManager->getRepository(User::class)->findByRole("ROLE_ADMIN");
        $toAddresses = [];
        
        if(count($admins) > 0){
            foreach($admins as $admin) {
                $toAddresses[] = $admin->getUsername();
            }

            $email = (new TemplatedEmail())
            ->from(new Address('no-reply@socialplaces.io', 'SocialPlaces'))
            ->to(...$toAddresses)
            ->subject('New point of contact Created')
            ->htmlTemplate('contact/contact-created.html.twig')
            ->context([
                'name' => $contact->getFullName(),
                'store'=> $store->getName()
            ]);

            try {
                $mailer->send($email);
            } catch (\Exception $e) {
            
            }
        }

        return $this->json(['message' => 'Contact created']);
    }
}
