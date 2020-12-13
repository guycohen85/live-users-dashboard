/* Template */
const template = document.createElement("template");
template.innerHTML = `
<div part="form-wrapper" class="container login-form form-wrapper">
  <h2 part="h2">Sign in to your account</h2>
  <form part="form" method="POST" action="/">
    <input part="input" name="form" value="login" type="hidden" />
    <label part="label">Email</label>
    <input required part="form-input" name="email" type="email" />
    <label part="label">Password</label>
    <input required part="form-input" name="password" type="password"/>
    <button part="button" type="submit">Login</button>
    <div class="sign-up-link link">Or <a part="link" href="#">Sign Up</a></div>
  </form>
</div>
`;


/* Web Component */
export default class LoginForm extends HTMLElement {
  constructor() {
    super();
    this.useTemplate();
  }

  connectedCallback() {
    this.shadowRoot.querySelector('.sign-up-link a').addEventListener("click", (event) => {
        var error = document.querySelector('.error'); 
        (error) ? error.remove() : '';
        var loginForm = document.createElement("register-form");
        document.querySelector('.auth').appendChild(loginForm);
        this.remove();
    });
  }

  useTemplate() {
    this.attachShadow({ mode: "open" });
    this.shadowRoot.append(template.content.cloneNode(true));
  }
}
