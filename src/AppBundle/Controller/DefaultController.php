<?php
/*
 * (c) lcavero <luiscaverodeveloper@gmail.com>
 */
namespace AppBundle\Controller;

use AppBundle\Exception\GithubServiceException;
use AppBundle\Service\GithubService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * indexAction
     * @param Request $request
     * @param LoggerInterface $logger
     * @param GithubService $githubService
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, LoggerInterface $logger, GithubService $githubService)
    {
        $form = $this->createFormBuilder()
            ->add('repository', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        $error = false;

        if($form->isSubmitted() && $form->isValid()){
            $repository = $form->getData()["repository"];

            try{
                // Get the repository words
                $words = $githubService->getWords($repository);

                // Success view
                return $this->render('default/words.html.twig', [
                    'words' => $words
                ]);

            }catch (\Exception $exception){
                if($exception instanceof GithubServiceException){
                    $error = $exception->getMessage();
                }else{
                    // Default error msg
                    $error = "The server is having problems to process your request, try again later";
                    $logger->error($exception->getMessage());
                }
            }

            // Error view
            return $this->render('default/github.html.twig', [
                'form' => $form->createView(), 'error' => $error,
            ]);
        }

        // First form view
        return $this->render('default/github.html.twig', [
            'form' => $form->createView(), 'error' => $error,
        ]);
    }

}
