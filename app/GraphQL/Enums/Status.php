<?php
namespace App\GraphQL\Enums;


    enum Status: int
    {
        
    case Balocked=-1;
    case None=0;
    case Active=1;
    case Inactive=2;
    case Susspend=3;
    case New=4;

    }

    enum MarriageStatus: int 
    {
    case None=0;
    case Related=1;
    case NotRelated=2;
    case Susspend=3;
    
    }

    enum ChildKind: int 
    {
    case None=0;
    case DirectChild=1;
    case MotherChild=2;
    case FatherChild=3;
    
    }

    enum ChildStatus: int 
    {
    case None=0;
    case WithFamily=1;
    case Separated=2;
    
    
    }

    enum PhysicalCondition: int 
    {
    case None=0;
    case Healthy=1;
    case Handicapped=2;
    case Dead=3;
    
    
    }
