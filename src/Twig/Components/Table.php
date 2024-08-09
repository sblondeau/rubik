<?php

namespace App\Twig\Components;
use ReflectionProperty;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\Component\TypeInfo\TypeResolver\TypeResolver;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;

#[AsLiveComponent]
final class Table
{
    use DefaultActionTrait;

    #[LiveProp()]
    public array $lines = [];

    public EntityRepository $repository;

    #[LiveProp()]
    public string $entity;

    #[LiveProp(writable:true)]
    public string $search = '';

    #[LiveProp()]
    public array $headers = [];

    #[LiveProp()]
    public array $searchFields = [];

    #[LiveProp()]
    public string $currentSort = 'id';

    #[LiveProp()]
    public string $currentDirection = 'ASC';

    public function __construct(private EntityManagerInterface $entityManager,)
    {

    }

    public function mount(string $entity, array $headers = [], array $searchFields = []) 
    {
        $this->entity = $entity;
        $this->repository = $this->entityManager->getRepository($this->entity);
        $this->headers = $headers ? $headers : $this->defautHeaders();
        $this->searchFields = $searchFields ? $searchFields : $this->defaultSearchFields();
        $records = $this->repository->findBy([]);
        $this->getLines($records);
    }

    private function defautHeaders()
    {
        if(!empty($this->headers)) {
            return $this->headers;
        }

        $first = $this->repository->findOneBy([]);
        if(!$first) {
            return [];
        }
        $reflectionExtractor = new ReflectionExtractor();

        return $reflectionExtractor->getProperties($this->entity);
    }

    private function defaultSearchFields()
    {
        if(!empty($this->searchFields)) {
            return $this->searchFields;
        }

        $first = $this->repository->findOneBy([]);
        if(!$first) {
            return [];
        }
        $reflectionExtractor = new ReflectionExtractor();
        
        $properties = $reflectionExtractor->getProperties($this->entity);
        $typeResolver = TypeResolver::create();
 
        return array_filter($properties, fn($property) => $typeResolver->resolve(new ReflectionProperty($this->entity, $property))->getBaseType()->getTypeIdentifier()->value === 'string');
    }

    public function getLines(array $records)
    {
        $this->lines = [];
        foreach($records as $record) {
            foreach($this->headers as $header) {
                $method = 'get'.ucfirst($header);
                $line[$header] = $record->$method();
            }
            $this->lines[] = $line;
        }
    }

    #[LiveAction]
    public function sort(#[LiveArg()] string $header) {
        $this->repository = $this->entityManager->getRepository($this->entity);
        $direction = $this->inverseDirection($header);

        $this->currentSort = $header;
        $this->currentDirection = $direction;
        $records = $this->repository->findBy([],[$this->currentSort => $this->currentDirection]);   
        $this->getLines($records);
    }

    private function inverseDirection(string $header = ''): string
    {
        $direction = 'ASC';
         if($header === $this->currentSort) {
            if($this->currentDirection === 'DESC') {
                $direction = 'ASC';
            } else {
                $direction = 'DESC';
            }
        }
           
        return $direction;       
   }

    #[LiveAction]
    public function filter() {
        $this->repository = $this->entityManager->getRepository($this->entity);
        $query = $this->repository->createQueryBuilder('e');
        foreach($this->searchFields as $searchField) {
            $query->orWhere("LOWER(e.$searchField) LIKE :search")
                ->setParameter('search', '%' . strtolower($this->search .'%'))
            ;
        }
        $query->orderBy('e.'.$this->currentSort, $this->currentDirection);

        $records = $query->getQuery()->getResult();
        $this->getLines($records);
    }

}





