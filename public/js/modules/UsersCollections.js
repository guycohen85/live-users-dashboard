/* Template */
const template = document.createElement("template");
template.innerHTML = `
<style>.user-row{display:table-row;}</style>

    <table cellspacing="0" cellpadding="0" part="table" class="users-list">
      <tr part="table-head">
        <th part="th">Username</th>
        <th part="th">Status</th>
        <th part="th">Login Time</th>
        <th part="th">Last Update Time</th>
        <th part="th">User Ip</th>
      </tr>
    </table>
    
`;

/* Web Component */
export default class UsersCollections extends HTMLElement {
  constructor() {
    super();
    this.usersListElement;
    this.users;
    this.currentUser = JSON.parse(this.dataset.user);
    this.getUsers();
  }

  init(users) {
    this.users = users;
    //console.log(this.users);
    this.useTemplate();
    this.setUsersToTemplate();
    this.updateUserDataApi();
  }

  getUsers() {
    fetch("/?users").then((response) => {
      response.text().then((users) => {
        this.init(JSON.parse(users));
      });
    });
  }

  setUsersToTemplate() {
    this.users.forEach((user) => {
      //if same user ignore
      if(user.email !== this.currentUser.email){
        this.setUser(user);
      }
    });
  }

  setUser(user) {
    var customUser = document.createElement("custom-user");
    customUser.user = user;
    customUser.setAttribute("class", "user-row");

    this.usersListElement.appendChild(customUser);
  }

  updateUserDataApi() {
    setInterval(() => {
      const data = new URLSearchParams("update_users=1");
      fetch("/", {
        method: "post",
        body: data,
        credentials: "same-origin",
      }).then((response) => {
        response.text().then((users) => {
          this.users = JSON.parse(users);
          var customUsers = this.shadowRoot.querySelectorAll('custom-user');
          customUsers.forEach( (customUser) => {
            customUser.remove();
          });
          this.setUsersToTemplate();
        });
      });
    }, 3000);
  }

  useTemplate() {
    this.attachShadow({ mode: "open" });
    this.shadowRoot.append(template.content.cloneNode(true));
    this.usersListElement = this.shadowRoot.querySelector(".users-list");
  }
}
