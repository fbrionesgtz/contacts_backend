<?php
namespace src\controller;

use src\DAO\ContactDAO;

class ContactController {
    private $db;
    private $requestMethod;
    private $contactId;

    private $contactDAO;

    public function __construct($db, $requestMethod, $contactId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->contactId = $contactId;

        $this->contactDAO = new ContactDAO($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->contactId) {
                    $response = $this->getContact($this->contactId);
                } else {
                    $response = $this->getAllContacts();
                };
                break;
            case 'POST':
                $response = $this->createContact();
                break;
            case 'PUT':
                $response = $this->updateContact($this->contactId);
                break;
            case 'DELETE':
                $response = $this->deleteContact($this->contactId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllContacts()
    {
        $result = $this->contactDAO->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getContact($id)
    {
        $result = $this->contactDAO->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createContact()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateContact($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->contactDAO->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateContact($id)
    {
        $result = $this->contactDAO->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateContact($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->contactDAO->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteContact($id)
    {
        $result = $this->contactDAO->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->contactDAO->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateContact($input)
    {
        if (!isset($input['firstname'])) {
            return false;
        }
        if (!isset($input['lastname'])) {
            return false;
        }
        if (!isset($input['email'])) {
            return false;
        }
        if (!isset($input['phoneNumber'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
