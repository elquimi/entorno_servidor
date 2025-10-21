<?php

class task{
public function __construct(
    private string $title,
    private string $description,
    private bool $completed,
    private string $filePath,
    private string $fileName,

){}
public function markCompleted(): void{
    $this->completed = true;
}


public function getInfo(): string{
    return '<div class="task">
        <h2>' . htmlspecialchars($this->title) . '</h2>
        <p>' . nl2br(htmlspecialchars($this->description)) . '</p>
        <p>Status: ' . ($this->completed ? 'Completed' : 'Pending') . '</p>
        <p>File: <a href="' . htmlspecialchars($this->filePath) . '">' . htmlspecialchars($this->fileName) . '</a></p>
    </div>';

}


public function getTitulo(): string{
    return $this->title;
}



public function getDescripcion(): string{
    return $this->description;
}
public function getFilePath(): string{
    return $this->filePath;
}
public function getFileName(): string{
    return $this->fileName;
}

public function isCompleted(): bool{
    return $this->completed;

}

public function setDescripcion(string $description): void{
    $this->description = $description;
}
public function setFilePath(string $filePath): void{
    $this->filePath = $filePath;
}
public function setFileName(string $fileName): void{
    $this->fileName = $fileName;
}


public function setTitle(string $title): void{
    $this->title = $title;
}
public function setCompleted(bool $completed): void{
    $this->completed = $completed;
}

















}











?>