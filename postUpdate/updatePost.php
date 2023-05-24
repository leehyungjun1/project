<?php 
class UpdatePostHandler {
    private EventDispatcher $eventDispatcher;
    private PostUpdater $postUpdater;

    public function __construct(EventDispatcher $eventDispatcher, postUpdater $postUpdater)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->postUpdater = $postUpdater;
    }

    public function handle(UpdatePost $command): void
    {
        //change the code here
        try {
            //UpdatePost 명령에서 제공된 데이터를 기반으로 새로운 BlogPost 객체를 생성합니다.
            $blogPost = new BlogPost(
                $command->getPostId(),
                $command->getTitle(),
                $command->getContent()
            );

            //PostUpdater 서비스를 사용하여 게시물을 업데이트 합니다.
            $this->postUpdater->update($blogPost);

            //postUpdated이벤트를 발송 합니다.
            $this->eventDispatcher->dispatch(
                new PostUpdated($command->getPostId(), $command->getUserId())
            );
        } catch (PostDoesNowExist $exception) {

        } catch (PostBlockedForEditing $exception) {

        } catch (Throwable $exception) {}
    }
}

//The UpdatePost object used as an argument for the handle() method is defined as follows :
class UpdatePost
{
    private int $postId;
    private int $userId;
    private string $title;
    private string $content;

    public function __construct( int $postId, int $userId, string $title, string $content) {
        $this->postId = $postId;
        $this->userId = $userId;
        $this->title = $title;
        $this->content= $content;
    }

    public function getPostId(): int { 
        return $this->postId;
    }
    public function getUserId(): int {
        return $this->userId;
        }
    public function getTitle(): string { 
        return $this->title;
    }
    public function getContent(): string { 
        return $this->content;
    }
}

    //The handler has access to two internal services
    //a post updater;
    //an event dispatcher.
    //They will be used to perform all necessary operations within the handler.
    //Using the PostUpdater
    //This Service is reponsible for updating the blog post. It implements the following interface
    
    interface PostUpdater
    {
        /**
         * @throws PostDoesNotExist
         * @throws PostBlockedForEditing
         * @throws \Throwable
         */

         public function update(BlogPost $post) : void;
    }

    //The BlogPost is an object that encapsulates the blog post and checks if the title is not too long( more than 20 characters). Its constructor looks like this
    class BlogPost
    {
        /**
         * @throws TitleTooLong
         */

         public function __construct(int $postId, string $title, string $content)
         {
            
         }
    }

    //Using the EventDispatcher
    //When the handler finishes updateing the post, a proper event needs to be dispatched using the EventDispatcher.

    interface EventDispatcher
    {
        public function dispatch(Event $event) : void;
    }

   //For the sake of this task, there is only a single class implementing the evnet PostUpdated its constuctor is shown below:

    class PostUpdated implements Event
    {
        public function __construct(int $postId, int $userId)
        {
            //...
        }
    }

    //For the sake of clarity, you can safely assume that:
    //All classes mentioned in the task are in the same director;
    //there is no need to include them.
    //You do not have to write your own implementations of any class provided here; please assume they already exist.
    //No exception/error will be thrown within tis task unless it is mentioned in the proper class description.

    //Hints
    //Look closely at the object contracts presented in the Assumptions section for method arguments and possible throwables.
    //Think carefully about what error/exception you need to catch( or not), and in what order.
    //if the tests throw errors concerning not imported classes, make sure to check for possible spelling mistakes. There is no need of writing any use statements in this task.

    //You will be using PHP 8.0 this task

    class PostDoesNowExist extends Exception {

    }
