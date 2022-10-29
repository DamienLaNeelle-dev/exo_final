import React, { useEffect, useState } from "react";

function Possessions() {
  const [error, setError] = useState(null);
  const [isLoaded, setIsLoaded] = useState(false);
  const [users, setUsers] = useState([]);
  //   const [possessions, setPossessions] = useState([]);

  useEffect(() => {
    fetchUsers();
    // fetchPossessions();
  }, []);

  const fetchUsers = () => {
    fetch(`http://127.0.0.1:8000/users`)
      .then((res) => res.json())
      .then(
        (result) => {
          setIsLoaded(true);
          console.log(result);
          setUsers(result);
        },
        (error) => {
          setIsLoaded(true);
          setError(error);
        }
      );
  };

  //   const fetchPossessions = () => {
  //     fetch(`http://127.0.0.1:8000/possessions`)
  //       .then((res) => res.json())
  //       .then(
  //         (result) => {
  //           setIsLoaded(true);
  //           console.log(result);
  //           setPossessions(result);
  //         },
  //         (error) => {
  //           setIsLoaded(true);
  //           setError(error);
  //         }
  //       );
  //   };

  if (error) {
    return <div> Erreur : {error.message}</div>;
  } else if (!isLoaded) {
    return <div>Chargement...</div>;
  } else {
    return (
      <div className="card" style="width: 18rem;">
        <div className="card-body">
          {users.map((user) => (
            <h5 key={user.id} className="card-title">
              {user.prenom} {user.nom}
            </h5>
          ))}
          <p className="card-text">
            Some quick example text to build on the card title and make up the
            bulk of the card's content.
          </p>
          <a href="#" className="btn btn-primary">
            Go somewhere
          </a>
        </div>
      </div>
    );
  }
}

export default Possessions;
