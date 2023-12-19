export default function authHeader() {
    let user = JSON.parse(localStorage.getItem('user'));
  
    if (user && user.authorization.token) {
      return { Authorization: 'Bearer ' + user.authorization.token };
    } else {
      return {};
    }
  }