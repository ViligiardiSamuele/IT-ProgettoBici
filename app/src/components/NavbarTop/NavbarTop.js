import "./NavbarTop.css";
import Container from "react-bootstrap/Container";
import Navbar from "react-bootstrap/Navbar";
import Button from "react-bootstrap/Button";
import Nav from "react-bootstrap/Nav";
import { Link } from "react-router-dom";

async function logout() {
  const request = {
    method: "POST",
    credentials: "include",
  };
  try {
    const response = await fetch(
      `http://localhost:8080/session/logout`,
      request
    );
    if (!response.ok) throw new Error("Network response was not ok");
    localStorage.removeItem("id_utente");
  } catch (error) {
    console.error("Errore durante il logout:", error);
  }
  window.location.reload();
}

export default function NavbarTop() {
  return (
    <>
      <Navbar expand="lg" className="bg-body-tertiary m-2 rounded">
        <Container fluid>
          <Navbar.Brand>IT-Progettobici</Navbar.Brand>
          <Navbar.Toggle aria-controls="navbarScroll" />
          <Navbar.Collapse id="navbarScroll">
            <Nav
              className="me-auto my-2 my-lg-0"
              navbarScroll
            >
              <Link to={"/"} className="nav-link">
                <i className="bi bi-house"></i> Home
              </Link>
              <Link to={"/gare"} className="nav-link">
                Tutte le gare
              </Link>
              <Link to={"/iscrizioni"} className="nav-link">
                Iscrizioni
              </Link>
            </Nav>
            <Navbar.Text>
            {localStorage.getItem("id_utente") == null ? (
              <Link to="/login">
                <Button>Login</Button>
              </Link>
            ) : (
              <Button onClick={logout}>
                {" "}
                <i className="bi bi-box-arrow-right"></i> Logout
              </Button>
            )}
          </Navbar.Text>
          </Navbar.Collapse>
        </Container>
      </Navbar>
    </>
  );
}
