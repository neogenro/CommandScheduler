<?php

namespace Neogen\CommandSchedulerBundle\Controller;

use Neogen\CommandSchedulerBundle\Entity\CommandEntity;
use Neogen\CommandSchedulerBundle\Form\CommandEntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CommandSchedulerController extends Controller
{

    /**
     * @return mixed
     */
    public function indexAction()
    {
        $scheduledCommands = $this->getDoctrine()->getManager()->getRepository(CommandEntity::class)->findAll();

        return $this->render('@CommandScheduler/list/index.html.twig', [
            'scheduledCommands' => $scheduledCommands
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function addAction(Request $request)
    {
        $data = new CommandEntity();
        $data->setLocked(false);
        $form = $this->createForm(CommandEntityType::class, $data);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
            return new RedirectResponse($this->generateUrl('command_scheduler_list'));
        }

        return $this->render('@CommandScheduler/detail/index.html.twig', [
            'scheduledCommandForm' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param CommandEntity $command
     * @return RedirectResponse
     */
    public function editAction(Request $request, CommandEntity $command)
    {
        $form = $this->createForm(CommandEntityType::class, $command);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($command);
            $em->flush();
            return new RedirectResponse($this->generateUrl('command_scheduler_list'));
        }

        return $this->render('@CommandScheduler/detail/index.html.twig', [
            'scheduledCommandForm' => $form->createView()
        ]);
    }

    /**
     * @param CommandEntity $commandEntity
     * @return RedirectResponse
     */
    public function unlockCommandAction(CommandEntity $commandEntity)
    {
        $commandEntity->setLocked(false);
        $em = $this->getDoctrine()->getManager();
        $em->persist($commandEntity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('command_scheduler_list'));
    }

    /**
     * @param CommandEntity $commandEntity
     * @return RedirectResponse
     */
    public function removeAction(CommandEntity $commandEntity)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($commandEntity);
        $em->flush();
        return new RedirectResponse($this->generateUrl('command_scheduler_list'));
    }
}
