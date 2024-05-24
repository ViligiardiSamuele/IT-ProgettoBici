import "./Home.css";
import NavbarTop from "../../components/NavbarTop/NavbarTop.js";
import Container from "react-bootstrap/esm/Container.js";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import UserInfo from "../../components/UserInfo/UserInfo.js";

export default function Home() {
  return (
    <div className="App">
      <NavbarTop />
      <Container>
        <Row>
          <Col sm={12} className="p-2">
            <Card className="p-2">
              <UserInfo />
            </Card>
          </Col>
        </Row>
        <Row>
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
