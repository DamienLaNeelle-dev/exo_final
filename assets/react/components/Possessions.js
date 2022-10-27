import React, { useEffect, useState } from "react";

function Possessions() {
  const [error, setError] = useState(null);
  const [isLoaded, setIsLoaded] = useState(false);
  const [possessions, setPossessions] = useState([]);

  useEffect(() => {
    fetchPossessions();
  }, []);

  const fetchPossessions = () => {
    fetch(`http://localhost:8000/apiPossessions`)
      .then((res) => res.json())
      .then(
        (result) => {
          setIsLoaded(true);
          console.log(result);
          setPossessions(result);
        },
        (error) => {
          setIsLoaded(true);
          setError(error);
        }
      );
  };

  if (error) {
    return <div> Erreur : {error.message}</div>;
  } else if (!isLoaded) {
    return <div>Chargement...</div>;
  } else {
    return (
      <div>
        <table className="table">
          <thead>
            <tr>
              <th scope="col">Id</th>
              <th scope="col">Nom</th>
              <th scope="col">Valeur</th>
              <th scope="col">Type</th>
            </tr>
          </thead>
          <tbody>
            {possessions.map((possession) => (
              <tr key={possession.id}>
                <th scope="row">{possession.id}</th>
                <td>{possession.nom}</td>
                <td>{possession.valeur}</td>
                <td>{possession.type}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    );
  }
}

export default Possessions;
