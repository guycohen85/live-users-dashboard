/* Template */
const template = document.createElement("template");
template.innerHTML = `
  <style>
  td{
    padding:15px 30px;
    border:none;
    border-bottom: 1px solid #e5e7eb;
    cursor:pointer;
    text-transform: capitalize;
  }
  .online {
    color: #4CAF50;
  }
  </style>
    <td part="td"><slot name="username"></slot></td>
    <td part="td"><slot name="status"></slot></td>
    <td part="td"><slot name="login-time"></slot></td>
    <td part="td"><slot name="last-update-time"></slot></td>
    <td part="td"><slot name="user-ip"></slot></td>
`;

/* Web Component */
export default class CustomUser extends HTMLElement {
  constructor(user) {
    super();

    this.user;

    this.useTemplate();
  }

  setUser(){
    this.shadowRoot.querySelector('td slot[name="username"]').textContent = this.user["username"];
    this.shadowRoot.querySelector('td slot[name="status"]').innerHTML = (this.user["status"] === 1) ? "<div class='online'>Online</div>" : "<div class='offline'>Offline</div>";
    this.shadowRoot.querySelector('td slot[name="login-time"]').textContent = this.formatDate(this.user["login_time"]);//TODO:fix login time
    this.shadowRoot.querySelector('td slot[name="last-update-time"]').textContent = this.formatDate(this.user["last_update_time"]);
    this.shadowRoot.querySelector('td slot[name="user-ip"]').textContent = this.user["user_ip"];
  }

  formatDate(timestamp){
    var date = new Date(timestamp * 1000);
    var hours = ( date.getHours() < 10 ) ? '0' + date.getHours() : date.getHours();
    var minutes = ( date.getMinutes() < 10 ) ? '0' + date.getMinutes() : date.getMinutes();
    var secodnds = ( date.getSeconds() < 10 ) ? '0' + date.getSeconds() : date.getSeconds();
    return hours + ":" + minutes + ":" +  secodnds;
  }

  connectedCallback() {
    //life cycle hook
    this.setUser();
    this.showPopupEvent();
  }

  showPopupEvent(){
    this.shadowRoot.addEventListener("click", () => {
      document.querySelectorAll("user-popup").forEach(el => {el.remove()});//make sure there is no popup
      var userPopup = document.createElement("user-popup");
      userPopup.user = this.user;
      document.querySelector('body').appendChild(userPopup);
    });
  }

  useTemplate() {
    this.attachShadow({ mode: "open" });
    this.shadowRoot.append(template.content.cloneNode(true));
  }
}
