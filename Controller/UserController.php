<?php

namespace Jlbs\AdminFOSUserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Jlbs\AdminFOSUserBundle\Entity\User;
use Jlbs\AdminFOSUserBundle\Form\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('JlbsAdminFOSUserBundle:User')->findAll();

        return $this->render(
            'JlbsAdminFOSUserBundle:User:index.html.twig',
            array(
                'entities' => $entities,
                'slug'=> 'user'
            )
        );
    }


    public function listAction(\Symfony\Component\HttpFoundation\Request $request)
    {

        $query = $this->getDoctrine()->getManager()
            ->createQueryBuilder()
            ->select('u')
            ->from('JlbsAdminFOSUserBundle:User', 'u');

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('username', 'email', 'nombre', 'p_apellido', 's_apellido', 'enabled', 'last_login', '');

        /**
         * search
         */
        if ($request->get('sSearch')) {
            $search = strtolower($request->get('sSearch'));
            $query
                //->orwhere("LOWER(u.username) LIKE '%:search%'")
                ->orwhere("LOWER(u.username) LIKE '%" . $search . "%'")
                ->orwhere("LOWER(u. nombre) LIKE '%" . $search . "%'")
                ->orwhere("LOWER(u.p_apellido) LIKE '%" . $search . "%'")
                ->orwhere("LOWER(u.s_apellido) LIKE '%" . $search . "%'")
                ->orwhere("LOWER(u.email) LIKE '%" . $search . "%'");


            // ->setParameter('search', ' % ' . strtolower($request->get('sSearch')) . ' % ');
        }
        /*
         * Ordering
         */

        for ($i = 0; $i < intval($request->get('iSortingCols')); $i++) {
            if ($request->get('bSortable_' . intval($request->get('iSortCol_' . $i))) == "true") {
                if (is_array($aColumns[($request->get('iSortCol_' . $i))])) {
                    $query->add(
                        'orderBy',
                        $aColumns[intval($request->get('iSortCol_' . $i))][0] . '.' . $aColumns[intval(
                            $request->get('iSortCol_' . $i)
                        )][1] . ' ' . $request->get('sSortDir_' . $i)
                    );
                } else {
                    $query->add(
                        'orderBy',
                        $query->getRootAlias() . '.' . $aColumns[intval(
                            $request->get('iSortCol_' . $i)
                        )] . ' ' . $request->get('sSortDir_' . $i)
                    );
                }
            }
        }
        $query = $query->getQuery();

        $total = 0;
        if ($request->get('iDisplayLength') != -1) {

            $paginator = $this->get('knp_paginator');
            $page = $page = ($request->get('iDisplayStart', 0) / $request->get('iDisplayLength')) + 1;
            $pagination = $paginator->paginate($query, $page, $request->get('iDisplayLength'));

            $list = $pagination;
            $total = $pagination->getTotalItemCount();
        } else {
            $list = $query->getResult();
            $total = count($list);
        }

        // $list = $query->getResult();
        // $total = count($list);
        /*
        * Building the json object to send to client side
        */
        $aaData = array();

        foreach ($list as $v) {

            $aaData[] = array(
                "0" => $v->getUsername(),
                "1" => $v->getEmail(),
                "2" => $v->getNombre(),
                "3" => $v->getPApellido(),
                "4" => $v->getSApellido(),
                "5" => ($v->isEnabled() ? 'Si' : 'No'),
                "6" => ($v->getLastLogin() ? $v->getLastLogin()->format('d/m/Y h:m:s a') : 'Not yet'),
                "7" => join(', ', $v->getRoles()),
                "8" => $this->renderView('JlbsAdminFOSUserBundle:User:actions.html.twig', array('entity' => $v)),
            );
        }

        $output = array(
            "sEcho" => intval($request->get('sEcho')),
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $total,
            "aaData" => $aaData,
        );


        $result = json_encode($output);

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent($result);

        return $response;
    }


    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JlbsAdminFOSUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'JlbsAdminFOSUserBundle:User:show.html.twig',
            array(
                'entity' => $entity,
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Displays a form to create a new User entity.
     *
     */
    public function newAction()
    {
        $entity = $this->getUserManager()->createUser();
        $form = $this->createForm(
            new UserType(),
            $entity,
            array('roles' => $this->container->getParameter('security.role_hierarchy.roles'))
        );

        return $this->render(
            'JlbsAdminFOSUserBundle:User:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Creates a new User entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = $this->getUserManager()->createUser();
        $form = $this->createForm(
            new UserType(),
            $entity,
            array('roles' => $this->container->getParameter('security.role_hierarchy.roles'))
        );
        $form->bind($request);

        if ($form->isValid()) {

            $this->getUserManager()->updateUser($entity, true);

            return $this->redirect($this->generateUrl('admin_user', array('id' => $entity->getId())));
        }

        return $this->render(
            'JlbsAdminFOSUserBundle:User:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JlbsAdminFOSUserBundle:User')->find($id);

//         ldd($entity->getLastLogin()->format('d/m/Y h:m:s a'));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createForm(
            new UserType(),
            $entity,
            array('roles' => $this->container->getParameter('security.role_hierarchy.roles'))
        );
        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'JlbsAdminFOSUserBundle:User:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Edits an existing User entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('JlbsAdminFOSUserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(
            new UserType(),
            $entity,
            array('roles' => $this->container->getParameter('security.role_hierarchy.roles'))
        );
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_edit', array('id' => $id)));
        }

        return $this->render(
            'JlbsAdminFOSUserBundle:User:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('JlbsAdminFOSUserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('user'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }


    private function getUserManager()
    {

        return $this->get('fos_user.user_manager');

    }

}
