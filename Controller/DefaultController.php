<?php

namespace Sketcher\Bundle\RestBundle\Controller;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Router;

class DefaultController extends Controller
{

    /**
     * @Route("/{entity}/{id}/", requirements={"id" = "\d+"}, name="rest_get_action")
     * @Method({"GET"})
     */
    public function restGetAction($entity, $id)
    {

        try {
            $this->get('router')->match("/$entity/{id}/");
            return new Response("URL GET: /" . $entity . "/{id}/ already exist.");
        } catch (ResourceNotFoundException $e) {

        }


        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT * FROM ' . $entity . ' WHERE `id` = :id';

        try {
            $stmt = $conn->prepare($sql);
        } catch (TableNotFoundException $e) {
            return new Response("Entity " . $entity . " not found.");
        }
        $stmt->execute(['id' => $id]);
        $res = $stmt->fetchAll();

        return new Response(json_encode($res));
    }

    /**
     * @Route("/{entity}/", name="rest_put_action")
     * @Method({"POST"})
     */
    public function restPutAction(Request $request, $entity)
    {

        try {
            $this->get('router')->match("/$entity/");
            return new Response("URL POST: /" . $entity . "/ already exist.");
        } catch (ResourceNotFoundException $e) {

        }

        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT * FROM ' . $entity . ' LIMIT 1, 1';

        try {
            $stmt = $conn->prepare($sql);
        } catch (TableNotFoundException $e) {
            return new Response("Entity " . $entity . " not found.");
        }

        $sql = 'INSERT INTO `' . $entity . "` ";

        $fields = $request->request->all();

        $sql_names = "";
        $sql_values = "";

        foreach ($fields as $field_name => $field_value) {
            $sql_names .= " `$field_name`,";
            $sql_values .= " :$field_name,";
        }
        $sql_names = substr($sql_names, 0, -1);
        $sql_values = substr($sql_values, 0, -1);

        $sql .= "($sql_names) VALUES ($sql_values)";

        try {
            $stmt = $conn->prepare($sql);
        } catch (Exception $exception) {
            return new Response($exception->getMessage());
        }
        $stmt->execute($fields);

        $res = new Response("", 201);

        return $res;
    }

    /**
     * @Route("/{entity}/{id}/update/", requirements={"id" = "\d+"}, name="rest_post_action")
     * @Method({"POST"})
     */
    public function restPostAction(Request $request, $entity, $id)
    {

        try {
            $this->get('router')->match("/$entity/{id}/update/");
            return new Response("URL POST: /" . $entity . "/{id}/update/ already exist.");
        } catch (ResourceNotFoundException $e) {

        }

        $conn = $this->getDoctrine()->getConnection();

        $sql = 'SELECT * FROM ' . $entity . ' WHERE `id` = :id';

        try {
            $stmt = $conn->prepare($sql);
        } catch (TableNotFoundException $e) {
            return new Response("Entity " . $entity . " not found.");
        } catch (Exception $e) {
            return new Response("Entity " . $entity . " not found by Id $id.");
        }

        $sql = 'UPDATE `' . $entity . "` SET";

        $fields = $request->request->all();

        foreach ($fields as $field_name => $field_value) {
            $sql .= " $field_name = :$field_name,";
        }
        $sql = substr($sql, 0, -1);

        $sql .= " WHERE `id` = :id";
        try {
            $stmt = $conn->prepare($sql);
        } catch (Exception $exception) {
            return new Response($exception->getMessage());
        }
        $fields["id"] = $id;
        $stmt->execute($fields);

        $res = new Response("", 202);

        return $res;
    }

    /**
     * @Route("/{entity}/{id}/delete/", requirements={"id" = "\d+"}, name="rest_delete_action")
     * @Method({"GET"})
     */
    public function restDeleteAction($entity, $id)
    {

        try {
            $this->get('router')->match("/$entity/{id}/delete/");
            return new Response("URL GET: /" . $entity . "/{id}/delete/ already exist.");
        } catch (ResourceNotFoundException $e) {

        }
        $conn = $this->getDoctrine()->getConnection();

        $sql = 'DELETE FROM ' . $entity . ' WHERE `id` = :id';

        try {
            $stmt = $conn->prepare($sql);
        } catch (TableNotFoundException $e) {
            return new Response("Entity " . $entity . " not found.");
        }

        $stmt->execute(array("id" => $id));

        $res = new Response("", 200);

        return $res;
    }
}
