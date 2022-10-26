import React, { useEffect, useState } from "react";

function Users() {
  const [error, setError] = useState(null);
  const [isLoaded, setIsLoaded] = useState(false);
  const [users, setUsers] = useState([]);

  useEffect(() => {
    fetch("http://127.0.0.1:8000/users")
      .then((res) => res.json())
      .then(
        (result) => {
          setIsLoaded(true);
          setUsers(result.users.data);
        },
        (error) => {
          setIsLoaded(true);
          setError(error);
        }
      );
  }, []);

  if (error) {
    return <div> Erreur : {error.message}</div>;
  } else if (!isLoaded) {
    return <div>Chargement...</div>;
  } else {
    <div>
      {users.map((user) => (
        <table className="table" key={user.id}>
          <thead>
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Prénom</th>
              <th scope="col">Nom</th>
              <th scope="col">email</th>
              <th scope="col">Adresse</th>
              <th scope="col">Tel</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">{user.id}</th>
              <td>{user.prenom}</td>
              <td>{user.nom}</td>
              <td>{user.email}</td>
              <td>{user.adress}</td>
              <td>{user.tel}</td>
            </tr>
          </tbody>
        </table>
      ))}
    </div>;
  }
}
// const Users = () => {

export default Users;
