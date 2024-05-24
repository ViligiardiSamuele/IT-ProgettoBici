import "./NavbarTop.css";
import Container from "react-bootstrap/Container";
import Navbar from "react-bootstrap/Navbar";
import Button from "react-bootstrap/Button";
import { Link } from "react-router-dom";

async function logout() {
  const request = {
    method: "POST",
    credentials: "include",
  };
  try {
    const response = await fetch(`http://localhost:8080/logout`, request);
    if (!response.ok) throw new Error("Network response was not ok");
    localStorage.removeItem("id_utente");
  } catch (error) {
    console.error("Errore durante il logout:", error);
  }
  window.location.reload();
}

export default function NavbarTop() {
  return (
    //<div className="bg-secondary p-1 m-2 rounded">
      <Navbar className="bg-body-tertiary shadow-lg p-1 m-2 bg-body-tertiary rounded">
        <Container>
          <Navbar.Brand href="/">IT-Progettobici</Navbar.Brand>
        </Container>
        <Container className="justify-content-end">
          <Navbar.Text>
            {localStorage.getItem("id_utente") == null ? (
              <Link to="/login">
                <Button>Login</Button>
              </Link>
            ) : (
              <Button onClick={logout}>Logout</Button>
            )}
          </Navbar.Text>
        </Container>
      </Navbar>
    //</div>
  );
}
