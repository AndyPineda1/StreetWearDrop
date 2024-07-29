/*
*   Controlador es de uso general en las páginas web del sitio público.
*   Sirve para manejar las plantillas del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'services/public/cliente.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
MAIN.classList.add('container');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'StreetWear Drop';
// Constante para establecer el elemento del título principal.
const MAIN_TITLE = document.getElementById('mainTitle');

/*  Función asíncrona para cargar el encabezado y pie del documento.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const loadTemplate = async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const DATA = await fetchData(USER_API, 'getUser');
    // Se comprueba si el usuario está autenticado para establecer el encabezado respectivo.
    if (DATA.session) {
        // Se verifica si la página web no es el inicio de sesión, de lo contrario se direcciona a la página web principal.
        if (!location.pathname.endsWith('login.html')) {
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `
                <header>
                    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
                        <div class="container">
                            <a class="navbar-brand" href="index.html"><img src="../../imagenes/Logo_streetwear.jpg" height="50" alt=""></a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                <div class="navbar-nav ms-auto">
                                <a class="nav-link" href="index.html"></i>Home</a>
                                <a class="nav-link" href="Man.html"></i>Men</a>
                                <a class="nav-link" href="women.html"></i>Women</a>
                                <a class="nav-link" href="kids.html"></i>Kids</a>
                                <a class="nav-link" href="cart.html"></i>Cart</a>
                                    <a class="nav-link" href="#" onclick="logOut()"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a>
                                </div>
                            </div>
                        </div>
                    </nav>
                </header>
            `);
        } else {
            location.href = 'index.html';
        }
    } else {
        // Se agrega el encabezado de la página web antes del contenido principal.
        MAIN.insertAdjacentHTML('beforebegin', `
            <header>
                <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
                    <div class="container">
                        <a class="navbar-brand" href="index.html"><img src="../../imagenes/Logo_streetwear.jpg" height="50" ></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                            <div class="navbar-nav ms-auto">
                                <a class="nav-link" href="index.html"></i>Home</a>
                                <a class="nav-link" href="Man.html"></i>Men</a>
                                <a class="nav-link" href="women.html"></i>Women</a>
                                <a class="nav-link" href="kids.html"></i>Kids</a>
                                <a class="nav-link" href="cart.html"></i>Cart</a>
                                <a class="nav-link" href="signup.html"><i class="bi bi-person"></i> Crear cuenta</a>
                                <a class="nav-link" href="login.html"><i class="bi bi-box-arrow-right"></i> Iniciar sesión</a>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
        `);
    }
    // Se agrega el pie de la página web después del contenido principal.
    MAIN.insertAdjacentHTML('afterend', `
    <footer class="footer mt-auto py-3">
    <div class="container">
        <div class="row">
            
            <div class="col-md-4">
                <h5>Navigation</h5>
                <p>Home</p>
                <p>Men</p>
                <p>Women</p>
                <p>Shoes</p>
                <p>Caps</p>
                <p>Kids</p>
                <p>Accessories</p>
            </div>
            
            <div class="col-md-4">
                <h5>Talk to us</h5>
                <p>streetwear_support@gmail.com</p>
                <p>+503 7581-7784</p>
            </div>
            
            <div class="col-md-4">
                <h5>Follow Us</h5>
                <p>@StreetWear_Drop</p>
            </div>
        </div>
    </div>
</footer>
    `);
}