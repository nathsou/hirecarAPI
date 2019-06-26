<?php
namespace App\Controller;

use App\Entity\Mail;
use App\Entity\ParkingSpotRental;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParkingSpotRentalController extends MediaTypeController
{
    public function __construct()
    {
        parent::__construct();
    }
    protected $endpoint = "parking_spot_rentals";
    /**
     * parking_spot_rentals
     * @Route("/parking_spot_rentals",methods={"POST"})
     *  condition="context.getMethod() in ['POST']
     */
    public function insertParkingSpotRental(Request $request)
    {
        $data = $this->inputMediaTypeConverter($request);
        $spot_rental = new ParkingSpotRental();
        $res = $spot_rental->insertParkingSpotRentalRequest(
            $data["start_date"],
            $data["end_date"],
            $data["car_id"],
            $data["parking_lot_id"]
        );
        $mail = new Mail();
        $name = $data["firstname"] . " " . $data["lastname"];
        $subject = 'Confirmation de reservation';
        $draft = "Bonjour $name, <br/>Votre réservation de place de parking du " . $data["start_date"] . " au  " . $data["end_date"]  . " a bien été prise en compte.<br/> Bien cordialement,<br/> L'équipe HireCar.<br/><img width='100px' src='data:image/png;base64,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'/>";
        $mail->sendMessage(
            $subject,
            $data["email"],
            $name,
            $draft
        );
        return $this->handleResponse($request, $res);
    }
    /**
     * parking_spot_rentals
     * @Route("/parking_spot_rentals",methods={"GET"})
     *  condition="context.getMethod() in ['GET']
     */
    public function getParkingSpotRentals(Request $request)
    {
        $spot_rental = new ParkingSpotRental();
        return $this->handleResponse($request, $spot_rental->getParkingSpotRentalsRequest($request));
    }
    /**
     * parking_spot_rentals
     * @Route("/parking_spot_rentals/{id}",methods={"DELETE"})
     * condition="context.getMethod() in ['DELETE']
     */
    public function deleteParkingSpotRental(Request $request)
    {
        $id = $request->get('id');
        if (
            isset($id) && is_numeric($id)
        ) {
            $spot_rental = new ParkingSpotRental();
            $spot_rental->deleteParkingSpotRentalRequest($id);
            return $this->mediaTypeConverter($request);
        }
        return $this->handleResponse($request, [
            "msg" => "",
            "status" => Response::HTTP_BAD_REQUEST
        ]);
    }
}
