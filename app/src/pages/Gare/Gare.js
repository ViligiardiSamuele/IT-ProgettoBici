import { useEffect, useState } from "react";
import "./Gare.css";
import NavbarTop from "../../components/NavbarTop/NavbarTop";
import ListaGare from "../../components/ListaGare/ListaGare"
import { redirect } from "react-router-dom";

export default function Gare() {
  const [gare, setGare] = useState([]);

  async function loadGare() {
    const request = {
      method: "GET",
      credentials: "include",
    };

    const response = await fetch(`http://localhost:8080/gare`, request);
    if(response.status == 401)
      return redirect("/login");
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
        <h2>Tutte le gare</h2>
        <ListaGare gare={gare}/>
      </div>
    </div>
  );
}
