document.getElementById('userType').addEventListener('change', function () {
    var userType = this.value;
    if (userType === 'login') {
        document.getElementById('loginFields').style.display = 'block';
        document.getElementById('registerFields').style.display = 'none';
    } else if (userType === 'register') {
        document.getElementById('loginFields').style.display = 'none';
        document.getElementById('registerFields').style.display = 'block';
    } else {
        document.getElementById('loginFields').style.display = 'none';
        document.getElementById('registerFields').style.display = 'none';
    }
});
document.getElementById('authForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let userType = document.getElementById('userType').value;
    
    if (userType === 'register') {
        let formData = new FormData();
        formData.append('registerEmail', document.getElementById('registerEmail').value);
        formData.append('registerPassword', document.getElementById('registerPassword').value);

        fetch('php/procesar_registro.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Registro exitoso');
                window.location.href = 'aulas.html';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
});


document.addEventListener('DOMContentLoaded', function() {
    const userTypeSelect = document.getElementById('userType');
    const loginFields = document.getElementById('loginFields');
    const registerFields = document.getElementById('registerFields');

    userTypeSelect.addEventListener('change', function() {
        if (this.value === 'login') {
            loginFields.style.display = 'block';
            registerFields.style.display = 'none';
        } else if (this.value === 'register') {
            loginFields.style.display = 'none';
            registerFields.style.display = 'block';
        } else {
            loginFields.style.display = 'none';
            registerFields.style.display = 'none';
        }
    });
});

// loadHeader.js
document.addEventListener("DOMContentLoaded", function () {
    // Selecciona el contenedor donde se insertará el header
    const headerContainer = document.getElementById('header-container');

    // Carga el contenido del archivo header.html
    fetch('header.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('No se pudo cargar el header.');
            }
            return response.text();
        })
        .then(data => {
            // Inserta el contenido del header en el contenedor
            headerContainer.innerHTML = data;
        })
        .catch(error => {
            console.error('Error al cargar el header:', error);
        });
});
function filtrarArchivos() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.getElementById("tablaArchivos").getElementsByTagName("tr");
    let resultadosEncontrados = false;

    for (let i = 0; i < rows.length; i++) {
        let archivo = rows[i].getElementsByTagName("td")[0].textContent.toLowerCase();
        if (archivo.includes(input)) {
            rows[i].style.display = "";
            resultadosEncontrados = true;
        } else {
            rows[i].style.display = "none";
        }
    }

    // Mostrar mensaje si no hay resultados
    let mensajeNoResultados = document.getElementById("mensajeNoResultados");
    if (!resultadosEncontrados) {
        if (!mensajeNoResultados) {
            let nuevaFila = document.createElement("tr");
            nuevaFila.id = "mensajeNoResultados";
            nuevaFila.innerHTML = `<td colspan="4" class="text-center">No se encontraron resultados.</td>`;
            document.getElementById("tablaArchivos").appendChild(nuevaFila);
        }
    } else {
        if (mensajeNoResultados) {
            mensajeNoResultados.remove();
        }
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const botonModoNoche = document.getElementById("modoNocheBtn");
    const body = document.body;
    const navbar = document.querySelector(".navbar");


    // Manejar el envío del formulario
    document.getElementById('uploadForm').addEventListener('submit', function (event) {
        event.preventDefault();

        // Obtener los valores del formulario
        let fileName = document.getElementById('fileName').value;
        let fileType = document.getElementById('fileType').value;
        let fileInput = document.getElementById('fileInput').files[0];
        let today = new Date().toLocaleDateString();

        // Simular la subida del archivo (en un entorno real, esto se haría con PHP o un backend)
        let filePath = "archivos/" + fileInput.name; // Ruta simulada del archivo

        // Crear una nueva fila para la tabla
        let newRow = `
            <tr>
                <td>${fileName}</td>
                <td>${fileType}</td>
                <td>${today}</td>
                <td><a href="${filePath}" class="btn btn-success btn-sm" download><i class="fas fa-download"></i> Descargar</a></td>
            </tr>
        `;

        // Agregar la nueva fila a la tabla
        document.getElementById('tablaArchivos').innerHTML += newRow;

        // Cerrar el modal
        let uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
        uploadModal.hide();

        // Limpiar el formulario
        document.getElementById('uploadForm').reset();
    });

});
document.getElementById('uploadForm').addEventListener('submit', function (event) {
    event.preventDefault();

    let formData = new FormData();
    formData.append('fileInput', document.getElementById('fileInput').files[0]);
    formData.append('fileName', document.getElementById('fileName').value);
    formData.append('fileType', document.getElementById('fileType').value);

    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                let today = new Date().toLocaleDateString();
                let newRow = `
                <tr>
                    <td>${data.fileName}</td>
                    <td>${document.getElementById('fileType').value}</td>
                    <td>${today}</td>
                    <td><a href="${data.filePath}" class="btn btn-success btn-sm" download><i class="fas fa-download"></i> Descargar</a></td>
                </tr>
            `;
                document.getElementById('tablaArchivos').innerHTML += newRow;

                let uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadModal'));
                uploadModal.hide();
                document.getElementById('uploadForm').reset();
            } else {
                alert('Error al subir el archivo.');
            }
        })
        .catch(error => console.error('Error:', error));
    //efecto pop up
    const sidebar = document.getElementById("sidebar");
    const toggleBtn = document.getElementById("toggleBtn");

    toggleBtn.addEventListener("click", () => {
        sidebar.classList.toggle("active");
    });
    //Modal iniciar sesion
    document.addEventListener("DOMContentLoaded", function () {
        const userType = document.getElementById("userType");
        const loginFields = document.getElementById("loginFields");
        const registerFields = document.getElementById("registerFields");
        const authForm = document.getElementById("authForm");
    
        userType.addEventListener("change", function () {
            if (userType.value === "login") {
                loginFields.style.display = "block";
                registerFields.style.display = "none";
                authForm.action = "php/procesar_login.php";
            } else if (userType.value === "register") {
                loginFields.style.display = "none";
                registerFields.style.display = "block";
                authForm.action = "php/procesar_registro.php";
            } else {
                loginFields.style.display = "none";
                registerFields.style.display = "none";
            }
        });
    });
    
});