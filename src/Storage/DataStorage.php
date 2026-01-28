<?php

namespace App\Storage;

use App\Model;

class DataStorage
{

    /**
     * @var \PDO 
     */
    //Change to private
    public $pdo;

    public function __construct()
    {
        //Missing password parameter
        //Improve: Use environment variables or configuration files for database connection
        $this->pdo = new \PDO('mysql:dbname=task_tracker;host=127.0.0.1', 'user');
    }

    /**
     * @param int $projectId
     * @throws Model\NotFoundException
     */
    public function getProjectById($projectId)
    {
        //Direct SQL injection, use prepare statement instead
        //$stmt = $this->pdo->prepare('SELECT * FROM project WHERE id = ?');
        //$stmt->execute([$projectId]);
        $stmt = $this->pdo->query('SELECT * FROM project WHERE id = ' . (int) $projectId);

        if ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            return new Model\Project($row);
        }

        throw new Model\NotFoundException();
    }

    /**
     * @param int $project_id
     * @param int $limit
     * @param int $offset
     */
    //Fix variable name $project_id => $projectId, Missing type hint for $limit, $offset
    public function getTasksByProjectId(int $project_id, $limit, $offset)
    {
        //SQL Syntax error, PDO::query() not support placeholder, also it uses direct SQL injection, use prepare statement instead
        //$stmt = $this->pdo->prepare("SELECT * FROM task WHERE project_id = ? LIMIT ? OFFSET ?");
        //Or using $stmt->bindValue()
        //$stmt->execute([$projectId, $limit, $offset]);
        $stmt = $this->pdo->query("SELECT * FROM task WHERE project_id = $project_id LIMIT ?, ?");
        $stmt->execute([$limit, $offset]);

        $tasks = [];
        foreach ($stmt->fetchAll() as $row) {
            $tasks[] = new Model\Task($row);
        }

        return $tasks;
    }

    /**
     * @param array $data
     * @param int $projectId
     * @return Model\Task
     */
    // Missing type hint for $projectId
    public function createTask(array $data, $projectId)
    {
        //Need to validate data first
        $data['project_id'] = $projectId;

        $fields = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($v) {
            return is_string($v) ? '"' . $v . '"' : $v;
        }, $data));
        //Using prepare and execute instead
        //$stmt = $this->pdo->prepare("INSERT INTO tasks (title, status, project_id) VALUES (?, ?, ?)");
        //$stmt->execute([$data['title'], $data['status'], $projectId]);
        //Also can implement transaction -> commit / rollback =>  Data consistency guaranteed
        //Or make the query more readable using sprintf
        $this->pdo->query("INSERT INTO task ($fields) VALUES ($values)");
        //Using $this->pdo->lastInsertId() instead
        $data['id'] = $this->pdo->query('SELECT MAX(id) FROM task')->fetchColumn();

        return new Model\Task($data);
    }
}
