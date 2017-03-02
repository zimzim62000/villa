<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:Home:index.html.twig', array(
            // ...
        ));
    }

    public function costAction()
    {
        return $this->render('AppBundle:Home:cost.html.twig', array(
            // ...
        ));
    }


    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm('AppBundle\Form\ContactType', $contact);

        if($request->getMethod() === "POST") {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $contact->setCreatedAt(new \DateTime('now'));
                $em->persist($contact);
                $em->flush($contact);

                $message = \Swift_Message::newInstance()
                    ->setSubject('Nouveau message de la villadésirée.com')
                    ->setFrom('villadesiree.guadeloupe@gmail.com')
                    ->setTo('villadesiree.guadeloupe@gmail.com')
                    ->setBcc('zimzim62000@gmail.com')
                    ->setBody(
                        $this->renderView(
                            'AppBundle:Home:newmessage.html.twig',
                            array(
                                'name' => $contact->getName(),
                                'email' => $contact->getEmail(),
                                'message' => $contact->getMessage()
                            )
                        ),
                        'text/html'
                    )
                ;
                $this->get('mailer')->send($message);


                return $this->render('AppBundle:Home:contact.html.twig', array(
                    'success' => true,
                    'contact' => $contact,
                    'form' => $form->createView()
                ));
            }
        }

        return $this->render('AppBundle:Home:contact.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        ));

    }


}
