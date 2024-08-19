<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUV</title>
    <script>
        async function updateOptions() {
            const type = document.getElementById("idOptions").value;
            const select = document.getElementById("dynamic-select");

            select.innerHTML = '';

            if (!type) return;

            try {
                const response = await fetch(`../data/getData.php?type=${encodeURIComponent(type)}`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const data = await response.json();
                
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.text = item.name;
                    select.add(option);
                });
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        function updateFileName() {
            const select = document.getElementById("dynamic-select");
            const hiddenNameField = document.getElementById("dynamic-select-name");
            hiddenNameField.value = select.options[select.selectedIndex].text;
        }

        function updateHiddenIdOptions() {
            const hiddenIdOptionsField = document.getElementById("idOptionsHidden");
            hiddenIdOptionsField.value = document.getElementById("idOptions").value;
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar bg-body-tertiary"></nav>
    </header>

    <div class="container mt-3">
        <section id="alerts"></section>
        <section id="form">
            <div class="container">
                <div class="container d-flex justify-content-center">
                    <label for="idOptions">Opciones:</label>
                    <select id="idOptions" name="idOptions" onchange="updateOptions(); updateHiddenIdOptions();">
                        <option value="">Seleccione una opción</option>
                        <option value="1">Universidad</option>
                        <option value="2">Área Conocimiento</option>
                        <option value="3">Género</option>
                        <option value="4">Orientación sexual</option>
                        <option value="5">Campus</option>
                    </select>
                </div>

                <form method="post" action="../bussiness/imagenAction.php" enctype="multipart/form-data" style="width: 50vw; min-width:300px;">
                    <input type="hidden" name="idOptionsHidden" id="idOptionsHidden">
                    <input type="hidden" name="dynamic-select-name" id="dynamic-select-name">
                    <div>
                        <label for="dynamic-select">Seleccione:</label>
                        <select id="dynamic-select" name="dynamic-select" onchange="updateFileName()">
                        </select>
                    </div>

                    <div class="mt-3">
                        <label for="imageUpload">Subir imagen:</label>
                        <input type="file" id="imageUpload" name="imageUpload" accept="image/*">
                    </div>

                    <div class="mt-3">
                        <button type="submit">Enviar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
