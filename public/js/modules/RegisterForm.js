/* Template */
const template = document.createElement("template");
template.innerHTML = `
<div part="form-wrapper" class="container register-form form-wrapper">
  <h2 part="h2">Create your account</h2>
  <form part="form" method="POST" action="/">
    <input name="form" value="register" type="hidden" />
    <label part="label">Email</label>
    <input required part="form-input" name="email" type="email" />
    <label part="label">Password</label>
    <input required part="form-input" name="password" type="password" />
    <button part="button" type="submit">Register</button>
    <div class="sign-in-link link">Or <a part="link" href="#">Sign In</a></div>
  </form>
</div>
`;


/* Web Component */
export default class RegisterForm extends HTMLElement {
  constructor() {
    super();
    this.useTemplate();
  }

  connectedCallback() {
    this.shadowRoot.querySelector('.sign-in-link a').addEventListener("click", (event) => {
        var error = document.querySelector('.error'); 
        (error) ? error.remove() : '';
        var loginForm = document.createElement("login-form");
        document.querySelector('.auth').appendChild(loginForm);
        this.remove();
    });
  }

  useTemplate() {//TODO: refactor - extends this method from parent
    this.attachShadow({ mode: "open" });
    this.shadowRoot.append(template.content.cloneNode(true));
  }
}
