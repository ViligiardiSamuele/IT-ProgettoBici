import "./Home.css";
import NavbarTop from "../../components/NavbarTop/NavbarTop.js";
import Container from "react-bootstrap/esm/Container.js";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";

export default function Home() {
  return (
    <div className="App">
      <NavbarTop />
      <Container>
        <Row>
          <Col sm={8} className="p-2">
            <Card className="p-2">
              <p>1</p>
            </Card>
          </Col>
          <Col sm={4} className="p-2">
            <Card className="p-2">
              <p>2</p>
            </Card>
          </Col>
        </Row>
      </Container>
    </div>
  );
}
