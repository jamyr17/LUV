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
        <img src="https://faunacr.com/wp-content/uploads/2022/04/shutterstock_1249664152-1-scaled.jpg" 
             alt="Zoomable Image" class="image" id="image">
        <div class="grid-overlay" id="grid-overlay">
            <!-- Divs for the 4x4 grid will be rendered here -->
        </div>

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
            image.style.transform = `scale(${zoomScale})`;
        });

        // Initialize the 4x4 grid
        for (let row = 0; row < 3; row++) {
            for (let col = 0; col < 3; col++) {
                const cell = document.createElement('div');
                cell.dataset.region = `${row + 1},${col + 1}`;
                cell.addEventListener('mouseenter', (event) => {
                    activeRegion = event.target.dataset.region;
                    zoomStart = Date.now();
                    console.log(`Entering region: ${activeRegion}`);
                });
                cell.addEventListener('mouseleave', (event) => {
                    if (activeRegion) {
                        const zoomDuration = Date.now() - zoomStart;
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

            fetch('../action/guardarSegmentacion.php', {
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

        
        function analizarAfinidades() {
            fetch('../action/calcularAfinidades.php', {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log(data.message);
                    console.log(data.afinidades);
                    alert("Afinidades calculadas y guardadas correctamente.");
                } else {
                    console.error("Error:", data.message);
                }
            })
            .catch(error => {
                console.error("Error en la solicitud:", error);
            });
        }
        
    </script>
</body>
</html>
