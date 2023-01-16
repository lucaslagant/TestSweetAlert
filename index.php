<?php
require 'db.php';

if (!empty($_POST)) {
  $errors = [];
  if (empty($_POST['task'])) {
    array_push($errors, 'Le champ tâche est requis.');
  }
  if (strlen($_POST['task']) < 3) {
    array_push($errors, 'Le champ tâche doit contenir au moins 3 caratères.');
  }
  if (!empty($errors)) {
    echo json_encode(['errors'=>$errors]);
    exit;
  }


  $task = strip_tags($_POST['task']);
  $req = $db->prepare('INSERT INTO tasks (content, created_at) VALUES (:content, NOW())');
  $req->execute([':content' =>$task]);
  echo json_encode(['success' => 'Tâche ajoutée.']);
  exit;

}
$req = $db->prepare('SELECT * FROM tasks ORDER BY id DESC');
$req->execute();
$tasks = $req->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <!--Bootstrap CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <title>Sweet Alert</title>
</head>
<body>
  <h1>Sweet Alert</h1>
  <form action="" id="form" method="POST">
 
    <div class="mb-3">
      <input type="text" class="form-control" name="task">
    </div>   
    <button type="submit" class="btn btn-primary">Ajouter</button>
  </form>
  <table class="table">
    <thead>
      <tr>
        <th>Tâche</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if ($tasks):
          foreach ($tasks as $task):?>
          <tr>
            <td><?=$task->content;?></td>
            <td><?=date('d-m-Y H:i', strtotime($task->created_at));?></td>
            <td><a class="delete" href="delete.php?id=<?=$task->id;?>"><button class="btn btn-danger">X</button></a></td>
          </tr>
      <?php
        endforeach; endif;
      ?>
    </tbody>
  </table>

    <!-- JS  -->
    <script src="script.js"></script>
    <!-- Bootstrap JS  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- Sweet Alert JS  -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>