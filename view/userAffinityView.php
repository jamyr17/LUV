<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Zoom with Matrix</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
            margin: 0;
        }
        .container {
            position: relative;
            width: 80%;
            max-width: 800px;
            overflow: hidden;
            border: 1px solid #ddd;
        }
        .image {
            width: 100%;
            transition: transform 0.3s ease;
        }
        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #000;
            color: #fff;
            padding: 5px 10px;
            border: none;
            cursor: pointer;
        }
        .grid-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-template-rows: repeat(3, 1fr);
            pointer-events: none;
        }
        .grid-overlay div {
            border: 1px solid rgba(0, 0, 0, 0.1);
            pointer-events: auto; /* Habilitar eventos en la cuadrícula */
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-button" onclick="goBack()">Back</button>
        <img src="https://www.travelexcellence.com/wp-content/uploads/2020/09/CANOPY-1.jpg" 
             alt="Zoomable Image" class="image" id="image">
        <div class="grid-overlay" id="grid-overlay"></div>
    </div>
    <button type="button" onclick="analizarAfinidades()">Analizar Afinidades</button>

    <script>
        const image = document.getElementById('image');
        const gridOverlay = document.getElementById('grid-overlay');
        let zoomScale = 1;
        let zoomStart = 0;
        let activeRegion = null;

        function goBack() {
            window.history.back();
        }

        // Zoom functionality
        image.addEventListener('wheel', (event) => {
            event.preventDefault();
            const zoomFactor = 0.1;
            if (event.deltaY < 0) {
                zoomScale += zoomFactor;
            } else {
                zoomScale = Math.max(1, zoomScale - zoomFactor);
            }
            // Usar plantilla de cadena correcta
            image.style.transform = `scale(${zoomScale})`;
        });

        // Initialize the 3x3 grid
        for (let row = 0; row < 3; row++) {
            for (let col = 0; col < 3; col++) {
                const cell = document.createElement('div');
                // Usar plantilla de cadena correcta
                cell.dataset.region = `${row + 1},${col + 1}`;
                cell.addEventListener('mouseenter', (event) => {
                    activeRegion = event.target.dataset.region;
                    zoomStart = Date.now();
                    // Usar plantilla de cadena correcta
                    console.log(`Entering region: ${activeRegion}`);
                });
                cell.addEventListener('mouseleave', (event) => {
                    if (activeRegion) {
                        const zoomDuration = Date.now() - zoomStart;
                        // Usar plantilla de cadena correcta
                        console.log(`Left region: ${activeRegion} after ${zoomDuration}ms`);
                        sendDataToBackend(activeRegion, zoomDuration, zoomScale);
                        activeRegion = null;
                        zoomStart = 0;
                    }
                });
                gridOverlay.appendChild(cell);
            }
        }

        // Send data to the backend
        function sendDataToBackend(region, duration, zoomScale) {
            const data = {
                region: region,
                duration: duration,
                zoomScale: zoomScale
            };

            fetch('../action/userAffinityAction.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch(error => console.error('Error:', error));
        }

        // Function to calculate affinities
        function analizarAfinidades() {
            fetch('../action/userAffinityAction.php', {
                method: 'GET'
            })
            .then(response => response.text()) // Cambiar a text() para ver la respuesta completa
            .then(data => {
              //  console.log("Respuesta completa:", data); // Depura la respuesta completa
                try {
                    const jsonData = JSON.parse(data); // Intenta analizar el JSON
                    if (jsonData.status === 'success') {
                        console.log(jsonData.message);
                        alert("Afinidad calculada correctamente: " + jsonData.message); // Mostrar mensaje de éxito
                    } else {
                        console.error("Error en el servidor:", jsonData.message);
                        alert("Error al calcular afinidades: " + jsonData.message); // Mostrar mensaje de error
                    }
                } catch (error) {
                    console.error("Error al analizar el JSON:", error);
                    alert("Error en la respuesta del servidor."); // Mensaje en caso de error de JSON
                }
            })
            .catch(error => {
                console.error("Error en la solicitud:", error);
                alert("Error en la solicitud al servidor."); // Mensaje de error de solicitud
            });
        }


    </script>
</body>
</html>
