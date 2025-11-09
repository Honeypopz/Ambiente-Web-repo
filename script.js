class Authentication {
    constructor() {
        this.users = JSON.parse(localStorage.getItem('users')) || [];
        this.currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
        this.init();
    }

    init() {
        this.createDefaultAdmin();
        this.setupEventListeners();
    }

    createDefaultAdmin() {
        const adminExists = this.users.find(user => user.rol === 'administrador');
        
        if (!adminExists) {
            const defaultAdmin = {
                id: 'admin1',
                nombre: 'Administrador',
                email: 'adminVB@gmail.com',
                contraseña: 'admin',
                rol: 'administrador',
                fechaRegistro: new Date().toISOString()
            };
            
            this.users.push(defaultAdmin);
            localStorage.setItem('users', JSON.stringify(this.users));
        }
    }

    setupEventListeners() {
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', (e) => this.handleRegister(e));
        }

        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
    }

    handleRegister(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const userData = {
            id: Date.now().toString(),
            nombre: formData.get('nombre'),
            email: formData.get('email'),
            contraseña: formData.get('contraseña'),
            rol: 'cliente',
            fechaRegistro: new Date().toISOString()
        };

        if (this.users.find(user => user.email === userData.email)) {
            this.showMessage('El email ya se encuentra registrado', 'error');
            return;
        }

        if (userData.contraseña.length < 6) {
            this.showMessage('La contraseña requiere minimo 6 caracteres', 'error');
            return;
        }

        this.users.push(userData);
        localStorage.setItem('users', JSON.stringify(this.users));
        
        this.showMessage('Registro exitoso, redirigiendo al login', 'success');
        
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 2000);
    }

    handleLogin(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const email = formData.get('email');
        const contraseña = formData.get('contraseña');

        const user = this.users.find(u => u.email === email && u.contraseña === contraseña);
        
        if (user) {
            this.currentUser = user;
            localStorage.setItem('currentUser', JSON.stringify(user));
            
            this.showMessage(`¡Bienvenido ${user.nombre}!`, 'success');
            
            setTimeout(() => {
                if (user.rol === 'administrador') {
                    window.location.href = 'DashboardAdmin.html';
                } else {
                    window.location.href = 'DashboardCliente.html';
                }
            }, 1500);
        } else {
            this.showMessage('Credenciales incorrectas', 'error');
        }
    }

    logout() {
        this.currentUser = null;
        localStorage.removeItem('currentUser');
        window.location.href = 'inicioCuenta.html';
    }

    showMessage(text, type) {
        const messageDiv = document.getElementById('message');
        if (messageDiv) {
            messageDiv.textContent = text;
            messageDiv.className = `message ${type}`;
            messageDiv.style.display = 'block';
            
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
    }
}

//Inicializar
document.addEventListener('DOMContentLoaded', () => {
    new Authentication();
});

//Logout
function logout() {
    const auth = new Authentication();
    auth.logout();
}

function proceso(){
    var nombre = document.getElementById("nombre").value;
    if(nombre == ''){
    alert("Ingrese su nombre");
    }
}



