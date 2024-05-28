import { useEffect, useState } from "react";
import "./Gare.css";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import ListaGare from "../../components/ListaGare/ListaGare";
import { redirect } from "react-router-dom";
import Card from "react-bootstrap/Card";

export default function Gare() {
  const [gare, setGare] = useState([]);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare`, request);
    if (response.status == 401) return redirect("/login");
    const json = await response.json();
    setGare(json);
  }

  useEffect(() => {
    loadGare();
  }, []);

  return (
    <div className="App">
      <NavbarTop />
      <div className="mx-auto mt-5 p-2" style={{ maxWidth: 800 }}>
        {localStorage.getItem("id_utente") != null ? (
          <>
            <h2>Tutte le gare</h2>
            <ListaGare gare={gare} />
          </>
        ) : (
          <Card className="m-5 p-5">
            <h1 className="text-center">
              Accedi per visualizzare le gare
            </h1>
          </Card>
        )}
      </div>
    </div>
  );
}
