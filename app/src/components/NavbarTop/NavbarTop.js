import "./NavbarTop.css";
import Container from "react-bootstrap/Container";
import Navbar from "react-bootstrap/Navbar";
import Button from "react-bootstrap/Button";
import { Link } from "react-router-dom";

export default function NavbarTop() {
  return (
    <Navbar className="bg-body-tertiary rounded m-2">
      <Container>
        <Navbar.Brand href="/">IT-Progettobici</Navbar.Brand>
      </Container>
      <Container className="justify-content-end">
        <Navbar.Text>
          <Link to="/login">
            <Button>Login</Button>
          </Link>
        </Navbar.Text>
      </Container>
    </Navbar>
  );
}
