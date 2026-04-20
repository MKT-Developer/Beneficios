<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Widget Test</title>
</head>

<body>

    <h2>Widget RH Beneficios</h2>

    <ul id="pilares"></ul>

    <script>
        fetch('https://beneficios.meracorporation.com/api/paises/mx/pilares')
            .then(res => res.json())
            .then(data => {
                const ul = document.getElementById('pilares');
                data.forEach(pilar => {
                    const li = document.createElement('li');
                    li.textContent = pilar.nombre;
                    ul.appendChild(li);
                });
            })
            .catch(err => console.error(err));
    </script>

</body>

</html>