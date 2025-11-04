<?php
// src/Entity/Product.php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $category = null;

    // Campos de precios - CAMBIADOS A INTEGER
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price1 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price4 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price6 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price8 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price16 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price20 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price32 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price36 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price40 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price48 = null;
    
    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $price50 = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $stock = true;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'image')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    // ---------------- Getters y Setters ----------------

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): self { $this->name = $name; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): self { $this->description = $description; return $this; }
    public function getCategory(): ?string { return $this->category; }
    public function setCategory(string $category): self { $this->category = $category; return $this; }

    // Getters y Setters precios - CAMBIADOS A INTEGER
    public function getPrice1(): ?int { return $this->price1; }
    public function setPrice1(?int $price1): self { $this->price1 = $price1; return $this; }
    
    public function getPrice4(): ?int { return $this->price4; }
    public function setPrice4(?int $price4): self { $this->price4 = $price4; return $this; }
    
    public function getPrice6(): ?int { return $this->price6; }
    public function setPrice6(?int $price6): self { $this->price6 = $price6; return $this; }
    
    public function getPrice8(): ?int { return $this->price8; }
    public function setPrice8(?int $price8): self { $this->price8 = $price8; return $this; }
    
    public function getPrice16(): ?int { return $this->price16; }
    public function setPrice16(?int $price16): self { $this->price16 = $price16; return $this; }
    
    public function getPrice20(): ?int { return $this->price20; }
    public function setPrice20(?int $price20): self { $this->price20 = $price20; return $this; }
    
    public function getPrice32(): ?int { return $this->price32; }
    public function setPrice32(?int $price32): self { $this->price32 = $price32; return $this; }
    
    public function getPrice36(): ?int { return $this->price36; }
    public function setPrice36(?int $price36): self { $this->price36 = $price36; return $this; }
    
    public function getPrice40(): ?int { return $this->price40; }
    public function setPrice40(?int $price40): self { $this->price40 = $price40; return $this; }
    
    public function getPrice48(): ?int { return $this->price48; }
    public function setPrice48(?int $price48): self { $this->price48 = $price48; return $this; }
    
    public function getPrice50(): ?int { return $this->price50; }
    public function setPrice50(?int $price50): self { $this->price50 = $price50; return $this; }

    public function getStock(): ?bool { return $this->stock; }
    public function setStock(bool $stock): self { $this->stock = $stock; return $this; }
    public function getStockText(): string { return $this->stock ? 'Sí' : 'No'; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): self { $this->image = $image; return $this; }

    public function setImageFile(?File $imageFile = null): void {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    public function getImageFile(): ?File { return $this->imageFile; }
    public function getUpdatedAt(): ?\DateTimeImmutable { return $this->updatedAt; }
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self { $this->updatedAt = $updatedAt; return $this; }

    public function __toString(): string {
        return $this->name ?? 'Nuevo Producto';
    }

    // ---------------- Métodos helper ----------------

    // Devuelve todos los precios disponibles
    public function getAvailablePrices(): array
    {
        $quantities = [1, 4, 6, 8, 16, 20, 32, 36, 40, 48, 50];
        $available = [];
        foreach ($quantities as $qty) {
            $price = $this->getPriceForQuantity($qty);
            if ($price !== null) {
                $available[$qty] = $price;
            }
        }
        return $available;
    }

    // Devuelve precio para cantidad específica
    public function getPriceForQuantity(int $quantity): ?int
    {
        $method = 'getPrice' . $quantity;
        return method_exists($this, $method) ? $this->$method() : null;
    }

    // ---------------- Nuevo: setter para precios formateados ----------------
    public function setAvailablePrices(array $prices): self
    {
        foreach ($prices as $qty => $price) {
            $method = 'setPrice' . $qty;
            if (method_exists($this, $method)) {
                $this->$method($price);
            }
        }
        return $this;
    }

    // Métodos de formato - SIMPLIFICADOS
    public function getFormattedPriceForQuantity(int $quantity): ?string
    {
        $price = $this->getPriceForQuantity($quantity);
        if ($price === null) {
            return null;
        }
        
        // Directo a formato argentino sin decimales
        return number_format($price, 0, ',', '.');
    }

    public function getMinFormattedPrice(): ?string
    {
        $prices = $this->getAvailablePrices();
        if (empty($prices)) {
            return null;
        }
        
        $minPrice = min($prices);
        return number_format($minPrice, 0, ',', '.');
    }
    // src/Entity/Product.php

// ... después de getMinFormattedPrice() ...

/**
 * Obtiene el precio mínimo numérico (sin formato)
 */
    public function getMinPrice(): ?int
   {
    $prices = $this->getAvailablePrices();
    if (empty($prices)) {
        return null;
    }
    
    return min($prices);
   }
}