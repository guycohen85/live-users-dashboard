/* Template */
const template = document.createElement("template");
template.innerHTML = `
<div part="popup-wrapper" class="popup-wrapper">
  <div part="popup" class="popup">
    <div part="close" class="close">X</div>
    <div>
      <div part="info"><span part="user-data">User’s User-Agent: </span><slot name="user-agent"></slot></div>
      <div part="info"><span part="user-data">Register time: </span><slot name="register-time"></slot></div>
      <div part="info"><span part="user-data">Logins count: </span><slot name="logins-count"></slot></div>
    </div>
  </div>
</div>
`;

/* Web Component */
export default class UserPopup extends HTMLElement {
  constructor() {
    super();

    this.user;

    this.useTemplate();
  }

  setUserData() {
    this.shadowRoot.querySelector(
      'slot[name="user-agent"]'
    ).textContent = this.user["user_agent"];
    this.shadowRoot.querySelector(
      'slot[name="register-time"]'
    ).textContent = this.formatDate(this.user["register_time"]);
    this.shadowRoot.querySelector(
      'slot[name="logins-count"]'
    ).textContent = this.user["logins_count"];
  }

  formatDate(timestamp){
    var date = new Date(timestamp * 1000);
    var formatDate = date.constructor().split(" GMT");
    return formatDate[0] ;
  }

  connectedCallback() {
    //life cycle hook
    this.setUserData();
    this.shadowRoot.querySelector('.close, .popup-wrapper').addEventListener("click", (e) => {
      if(e.path[0].className === 'close' || e.path[0].className === 'popup-wrapper' ){
        this.remove();
      }
    });
  }

  useTemplate() {
    this.attachShadow({ mode: "open" });
    this.shadowRoot.append(template.content.cloneNode(true));
  }
}
