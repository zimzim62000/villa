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
                $em->persist($contact);
                $em->flush($contact);

                return $this->redirectToRoute('app_home_index');
            }
        }

        return $this->render('AppBundle:Home:contact.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView(),
        ));

    }


}
