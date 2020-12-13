import CustomUser from "./modules/CustomUser.js";
import UsersCollections from "./modules/UsersCollections.js";
import userPopup from "./modules/userPopup.js";
import RegisterForm from "./modules/RegisterForm.js";
import LoginForm from "./modules/LoginForm.js";

customElements.define('custom-user', CustomUser);
customElements.define('users-collections', UsersCollections);
customElements.define('user-popup', userPopup);
customElements.define('register-form', RegisterForm);
customElements.define('login-form', LoginForm);
