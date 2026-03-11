<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Задачи</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        input, select, button {
            padding: 8px;
            margin: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .btn-delete {
            background: red;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-add {
            background: green;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Список задач</h1>

    <div>
        <input type="text" id="title" placeholder="Название">
        <input type="text" id="description" placeholder="Описание">
        <button class="btn-add" onclick="addTask()">Добавить</button>
    </div>

    <div id="error" class="error"></div>

    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody id="tasks">
        </tbody>
    </table>

    <script>
        // Загружаем задачи при открытии страницы
        loadTasks();

        function loadTasks() {
            fetch('/api/tasks')
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    var tasks = data.data || data;
                    var html = '';

                    for (var i = 0; i < tasks.length; i++) {
                        var task = tasks[i];
                        html += '<tr>';
                        html += '<td>' + task.title + '</td>';
                        html += '<td>' + (task.description || '-') + '</td>';
                        html += '<td>';
                        html += '<select onchange="changeStatus(' + task.id + ', this.value)">';
                        html += '<option value="new"' + (task.status == 'new' ? ' selected' : '') + '>Новая</option>';
                        html += '<option value="in_progress"' + (task.status == 'in_progress' ? ' selected' : '') + '>В работе</option>';
                        html += '<option value="done"' + (task.status == 'done' ? ' selected' : '') + '>Выполнена</option>';
                        html += '</select>';
                        html += '</td>';
                        html += '<td><button class="btn-delete" onclick="deleteTask(' + task.id + ')">Удалить</button></td>';
                        html += '</tr>';
                    }

                    if (tasks.length == 0) {
                        html = '<tr><td colspan="4">Задач пока нет</td></tr>';
                    }

                    document.getElementById('tasks').innerHTML = html;
                });
        }

        function addTask() {
            var title = document.getElementById('title').value;
            var description = document.getElementById('description').value;

            if (!title) {
                document.getElementById('error').innerHTML = 'Введите название';
                return;
            }

            fetch('/api/tasks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: title,
                    description: description
                })
            })
            .then(function(response) {
                if (response.ok) {
                    document.getElementById('title').value = '';
                    document.getElementById('description').value = '';
                    document.getElementById('error').innerHTML = '';
                    loadTasks();
                } else {
                    document.getElementById('error').innerHTML = 'Ошибка при добавлении';
                }
            });
        }

        function changeStatus(id, status) {
            fetch('/api/tasks/' + id, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    status: status
                })
            });
        }

        function deleteTask(id) {
            if (confirm('Удалить?')) {
                fetch('/api/tasks/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(function() {
                    loadTasks();
                });
            }
        }
    </script>
</body>
</html>
